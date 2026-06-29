<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VisiteurRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentDemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = VisiteurRequest::with(['visiteur', 'visiteurs', 'superviseur', 'service.departement', ...VisiteurRequest::RELATIONS_AUDIT])
            ->where('statut', '!=', 'brouillon');

        if ($request->filled('nom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('nom', 'like', '%'.$request->nom.'%'));
        }
        if ($request->filled('entreprise')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('entreprise', 'like', '%'.$request->entreprise.'%'));
        }
        if ($request->filled('date')) {
            $query->whereDate('date_prevue', $request->date);
        }
        if ($request->filled('superviseur')) {
            $query->whereHas('superviseur', fn ($q) => $q->where('name', 'like', '%'.$request->superviseur.'%'));
        }

        $demandes = $query->latest()->get();

        return view('agent-de-security.demandes.index', compact('demandes'));
    }

    public function show($id)
    {
        $demande = VisiteurRequest::with(['visiteur', 'visiteurs', 'superviseur', 'service.departement', ...VisiteurRequest::RELATIONS_AUDIT])->findOrFail($id);

        if ($demande->statut === 'en_attente') {
            $demande->update(['statut' => 'recu']);
        }

        return view('agent-de-security.demandes.show', compact('demande'));
    }

    public function valider(Request $request, $id)
    {
        $demande = VisiteurRequest::findOrFail($id);

        if (! in_array($demande->statut, ['en_attente', 'recu'])) {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $request->validate(['commentaire_securite' => 'nullable|string|max:1000']);

        $demande->update([
            'statut' => 'valide',
            'commentaire_securite' => $request->commentaire_securite,
            'valide_par' => Auth::id(),
            'date_validation' => now(),
        ]);

        NotificationService::notifierSuperviseur(
            $demande->superviseur_id,
            'Votre demande de visite a été validée.',
            'demande_validee'
        );

        return redirect()->route('agent.demandes.index')->with('success', 'Demande validée.');
    }

    public function refuser(Request $request, $id)
    {
        $demande = VisiteurRequest::findOrFail($id);

        if (! in_array($demande->statut, ['en_attente', 'recu'])) {
            return redirect()->back()->with('error', 'Cette demande a déjà été traitée.');
        }

        $request->validate(['commentaire_securite' => 'required|string|max:1000'], [
            'commentaire_securite.required' => 'Veuillez indiquer le motif du refus.',
        ]);

        $demande->update([
            'statut' => 'refuse',
            'commentaire_securite' => $request->commentaire_securite,
            'valide_par' => Auth::id(),
            'date_validation' => now(),
        ]);

        NotificationService::notifierSuperviseur(
            $demande->superviseur_id,
            'Votre demande de visite a été refusée.',
            'demande_refusee'
        );

        return redirect()->route('agent.demandes.index')->with('success', 'Demande refusée.');
    }

    public function visitesDuJour()
    {
        $demandes = VisiteurRequest::with(['visiteur', 'visiteurs', 'superviseur', 'service.departement', ...VisiteurRequest::RELATIONS_AUDIT])
            ->pourAccesSortie()
            ->orderBy('date_prevue')
            ->orderBy('heure_prevue')
            ->get();

        return view('agent-de-security.demandes.aujourdhui', compact('demandes'));
    }

    public function confirmerArrivee($id)
    {
        $demande = VisiteurRequest::findOrFail($id);

        if ($demande->statut !== 'valide') {
            return redirect()->back()->with('error', 'Seules les visites validées peuvent être enregistrées.');
        }

        if ($demande->date_prevue->isAfter(today())) {
            return redirect()->back()->with('error', 'Cette visite est prévue pour le '.$demande->date_prevue->format('d/m/Y').'.');
        }

        if ($demande->heure_arrivee) {
            return redirect()->back()->with('error', 'L\'arrivée a déjà été enregistrée.');
        }

        $demande->update([
            'heure_arrivee' => now(),
            'arrivee_par' => Auth::id(),
        ]);

        $visiteur = $demande->visiteurPrincipal();

        NotificationService::notifierSuperviseur(
            $demande->superviseur_id,
            'Le visiteur '.$visiteur->prenom.' '.$visiteur->nom.' est arrivé.',
            'visiteur_arrive'
        );

        return redirect()->back()->with('success', 'Entrée validée avec succès.');
    }

    public function enregistrerSortie($id)
    {
        $demande = VisiteurRequest::findOrFail($id);

        if (! $demande->heure_arrivee) {
            return redirect()->back()->with('error', 'Enregistrez d\'abord l\'arrivée du visiteur.');
        }

        if ($demande->heure_sortie) {
            return redirect()->back()->with('error', 'La sortie a déjà été enregistrée.');
        }

        if (! in_array($demande->statut, ['valide', 'termine'], true)) {
            return redirect()->back()->with('error', 'Cette visite ne peut pas être clôturée.');
        }

        $demande->update([
            'heure_sortie' => now(),
            'sortie_par' => Auth::id(),
            'statut' => 'termine',
        ]);

        return redirect()->back()->with('success', 'Sortie validée avec succès. Visite terminée.');
    }
}
