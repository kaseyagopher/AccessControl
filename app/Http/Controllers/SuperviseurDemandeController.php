<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Service;
use App\Models\Visiteur;
use App\Models\VisiteurRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperviseurDemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = VisiteurRequest::with(['visiteur', 'visiteurs', 'superviseur', 'service.departement', ...VisiteurRequest::RELATIONS_AUDIT])
            ->where('superviseur_id', Auth::id());

        if ($request->filled('nom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('nom', 'like', '%'.$request->nom.'%'));
        }
        if ($request->filled('entreprise')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('entreprise', 'like', '%'.$request->entreprise.'%'));
        }
        if ($request->filled('date')) {
            $query->whereDate('date_prevue', $request->date);
        }
        if ($request->filled('statut')) {
            $statut = $this->mapStatutFiltre($request->statut);
            if (is_array($statut)) {
                $query->whereIn('statut', $statut);
            } else {
                $query->where('statut', $statut);
            }
        }

        $demandes = $query->latest()->get();

        return view('superviseur.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('superviseur.demandes.create', $this->formData());
    }

    public function store(Request $request)
    {
        $data = $this->validateVnf($request);
        $envoyer = $request->input('action') === 'envoyer';

        $demande = $this->persistVnf($data, $request, null, $envoyer);

        $message = $envoyer
            ? 'VNF envoyée à la sécurité.'
            : 'VNF enregistrée en brouillon.';

        return redirect()->route('superviseur.demandes.index')->with('success', $message);
    }

    public function show($id)
    {
        $demande = $this->findOwnDemande($id);
        $demande->load(['visiteur', 'visiteurs', 'service.departement', ...VisiteurRequest::RELATIONS_AUDIT]);

        return view('superviseur.demandes.show', compact('demande'));
    }

    public function edit($id)
    {
        $demande = $this->findOwnDemande($id);
        $demande->load(['visiteur', 'visiteurs', 'service.departement']);

        if (! $demande->isEditableBySuperviseur()) {
            return redirect()->route('superviseur.demandes.show', $demande->id)
                ->with('error', 'Cette VNF ne peut plus être modifiée.');
        }

        return view('superviseur.demandes.edit', array_merge($this->formData(), compact('demande')));
    }

    public function update(Request $request, $id)
    {
        $demande = $this->findOwnDemande($id);

        if (! $demande->isEditableBySuperviseur()) {
            return redirect()->route('superviseur.demandes.show', $demande->id)
                ->with('error', 'Cette VNF ne peut plus être modifiée.');
        }

        $data = $this->validateVnf($request);
        $envoyer = $request->input('action') === 'envoyer';

        $this->persistVnf($data, $request, $demande, $envoyer);

        $message = $envoyer
            ? 'VNF mise à jour et envoyée à la sécurité.'
            : 'VNF mise à jour.';

        return redirect()->route('superviseur.demandes.index')->with('success', $message);
    }

    public function envoyer($id)
    {
        $demande = $this->findOwnDemande($id);

        if (! in_array($demande->statut, ['brouillon', 'en_attente'], true)) {
            return redirect()->back()->with('error', 'Cette VNF a déjà été traitée.');
        }

        $wasBrouillon = $demande->statut === 'brouillon';
        $demande->update(['statut' => 'en_attente']);

        if ($wasBrouillon) {
            $this->notifierNouvelleDemande($demande);
        }

        return redirect()->route('superviseur.demandes.index')
            ->with('success', 'VNF envoyée à la sécurité.');
    }

    public function observationAcces(Request $request, $id)
    {
        $demande = $this->findOwnDemande($id);

        $request->validate([
            'observation_acces' => 'required|string|max:2000',
        ], [
            'observation_acces.required' => 'Veuillez saisir une observation d\'accès.',
        ]);

        if (! $demande->heure_arrivee) {
            return redirect()->back()->with('error', 'L\'arrivée doit être enregistrée avant d\'ajouter une observation.');
        }

        $demande->update(['observation_acces' => $request->observation_acces]);

        return redirect()->back()->with('success', 'Observation d\'accès enregistrée.');
    }

    public function observationSortie(Request $request, $id)
    {
        $demande = $this->findOwnDemande($id);

        $request->validate([
            'observation_sortie' => 'required|string|max:2000',
        ], [
            'observation_sortie.required' => 'Veuillez saisir une observation de sortie.',
        ]);

        if (! $demande->heure_sortie) {
            return redirect()->back()->with('error', 'La sortie doit être enregistrée avant d\'ajouter une observation.');
        }

        $demande->update(['observation_sortie' => $request->observation_sortie]);

        return redirect()->back()->with('success', 'Observation de sortie enregistrée.');
    }

    private function findOwnDemande($id): VisiteurRequest
    {
        return VisiteurRequest::where('superviseur_id', Auth::id())->findOrFail($id);
    }

    private function formData(): array
    {
        $departements = Departement::with('services')->orderBy('nom')->get();
        $services = Service::with('departement')->orderBy('nom')->get();

        return compact('departements', 'services');
    }

    private function validateVnf(Request $request): array
    {
        return $request->validate([
            'visiteurs' => 'required|array|min:1|max:50',
            'visiteurs.*.nom' => 'required|string|max:100',
            'visiteurs.*.postnom' => 'nullable|string|max:100',
            'visiteurs.*.prenom' => 'required|string|max:100',
            'visiteurs.*.genre' => 'nullable|in:M,F,Autre',
            'visiteurs.*.telephone' => 'required|string|max:30',
            'visiteurs.*.entreprise' => 'required|string|max:150',
            'visiteurs.*.fonction' => 'nullable|string|max:100',
            'service_id' => 'required|exists:services,id',
            'motif' => 'required|string|max:2000',
            'date_prevue' => 'required|date|after_or_equal:today',
            'heure_prevue' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
            'action' => 'required|in:enregistrer,envoyer',
        ], [
            'visiteurs.required' => 'Ajoutez au moins un visiteur.',
            'visiteurs.min' => 'Ajoutez au moins un visiteur.',
            'service_id.required' => 'Veuillez sélectionner un service.',
            'date_prevue.after_or_equal' => 'La date de visite ne peut pas être dans le passé.',
            'document.mimes' => 'Le document doit être un PDF, Word ou une image.',
            'document.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ]);
    }

    private function persistVnf(array $data, Request $request, ?VisiteurRequest $demande, bool $envoyer): VisiteurRequest
    {
        $visiteurIds = [];
        foreach ($data['visiteurs'] as $visiteurData) {
            $visiteur = Visiteur::create([
                'nom' => $visiteurData['nom'],
                'postnom' => $visiteurData['postnom'] ?? null,
                'prenom' => $visiteurData['prenom'],
                'genre' => $visiteurData['genre'] ?? null,
                'telephone' => $visiteurData['telephone'],
                'entreprise' => $visiteurData['entreprise'],
                'fonction' => $visiteurData['fonction'] ?? null,
                'superviseur_id' => Auth::id(),
            ]);
            $visiteurIds[] = $visiteur->id;
        }

        $documentPath = $demande?->document_path;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        $statut = $envoyer
            ? 'en_attente'
            : (($demande && $demande->statut === 'en_attente') ? 'en_attente' : 'brouillon');
        $shouldNotify = $envoyer && ($demande === null || $demande->statut === 'brouillon');

        if ($demande) {
            $demande->update([
                'visiteur_id' => $visiteurIds[0],
                'service_id' => $data['service_id'],
                'motif' => $data['motif'],
                'date_prevue' => $data['date_prevue'],
                'heure_prevue' => $data['heure_prevue'],
                'nombre_visiteurs' => count($visiteurIds),
                'document_path' => $documentPath,
                'statut' => $statut,
            ]);
            $demande->visiteurs()->sync($visiteurIds);
        } else {
            $demande = VisiteurRequest::create([
                'visiteur_id' => $visiteurIds[0],
                'superviseur_id' => Auth::id(),
                'service_id' => $data['service_id'],
                'motif' => $data['motif'],
                'date_prevue' => $data['date_prevue'],
                'heure_prevue' => $data['heure_prevue'],
                'nombre_visiteurs' => count($visiteurIds),
                'document_path' => $documentPath,
                'statut' => $statut,
            ]);
            $demande->visiteurs()->attach($visiteurIds);
        }

        if ($shouldNotify) {
            $this->notifierNouvelleDemande($demande);
        }

        return $demande;
    }

    private function notifierNouvelleDemande(VisiteurRequest $demande): void
    {
        NotificationService::notifierSecurite(
            'Nouvelle VNF de '.Auth::user()->name.' ('.$demande->nombre_visiteurs.' visiteur(s))',
            'nouvelle_demande'
        );
    }

    private function mapStatutFiltre(string $filtre): string|array
    {
        return match ($filtre) {
            'valide' => 'valide',
            'en_attente' => ['brouillon', 'en_attente', 'recu'],
            'non_valide' => 'refuse',
            default => $filtre,
        };
    }
}
