<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Visiteur;
use App\Models\VisiteurRequest;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperviseurDemandeController extends Controller
{
    public function index(Request $request)
    {
        $query = VisiteurRequest::with(['visiteur', 'visiteurs'])
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

        $demandes = $query->latest()->get();

        return view('superviseur.demandes.index', compact('demandes'));
    }

    public function create()
    {
        return view('superviseur.demandes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'visiteurs' => 'required|array|min:1|max:50',
            'visiteurs.*.nom' => 'required|string|max:100',
            'visiteurs.*.prenom' => 'required|string|max:100',
            'visiteurs.*.telephone' => 'required|string|max:30',
            'visiteurs.*.entreprise' => 'required|string|max:150',
            'visiteurs.*.fonction' => 'nullable|string|max:100',
            'motif' => 'required|string|max:2000',
            'date_prevue' => 'required|date|after_or_equal:today',
            'heure_prevue' => 'required|string',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'visiteurs.required' => 'Ajoutez au moins un visiteur.',
            'visiteurs.min' => 'Ajoutez au moins un visiteur.',
            'date_prevue.after_or_equal' => 'La date de visite ne peut pas être dans le passé.',
            'document.mimes' => 'Le document doit être un PDF, Word ou une image.',
            'document.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ]);

        $visiteurIds = [];
        foreach ($data['visiteurs'] as $visiteurData) {
            $visiteur = Visiteur::create([
                'nom' => $visiteurData['nom'],
                'prenom' => $visiteurData['prenom'],
                'telephone' => $visiteurData['telephone'],
                'entreprise' => $visiteurData['entreprise'],
                'fonction' => $visiteurData['fonction'] ?? null,
                'superviseur_id' => Auth::id(),
            ]);
            $visiteurIds[] = $visiteur->id;
        }

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        $demande = VisiteurRequest::create([
            'visiteur_id' => $visiteurIds[0],
            'superviseur_id' => Auth::id(),
            'motif' => $data['motif'],
            'date_prevue' => $data['date_prevue'],
            'heure_prevue' => $data['heure_prevue'],
            'nombre_visiteurs' => count($visiteurIds),
            'document_path' => $documentPath,
            'statut' => 'en_attente',
        ]);

        $demande->visiteurs()->attach($visiteurIds);

        NotificationService::notifierSecurite(
            'Nouvelle demande de visite de '.Auth::user()->name.' ('.count($visiteurIds).' visiteur(s))',
            'nouvelle_demande'
        );

        return redirect()->route('superviseur.demandes.index')
            ->with('success', 'Demande de visite envoyée à la sécurité.');
    }

    public function archivages(Request $request)
    {
        $query = VisiteurRequest::with(['visiteur', 'visiteurs'])
            ->where('superviseur_id', Auth::id());

        if ($request->filled('nom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('nom', 'like', '%'.$request->nom.'%'));
        }
        if ($request->filled('prenom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('prenom', 'like', '%'.$request->prenom.'%'));
        }
        if ($request->filled('entreprise')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('entreprise', 'like', '%'.$request->entreprise.'%'));
        }
        if ($request->filled('annee')) {
            $query->whereYear('date_prevue', $request->annee);
        }
        if ($request->filled('date_debut')) {
            $query->whereDate('date_prevue', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_prevue', '<=', $request->date_fin);
        }
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }

        $demandes = $query->latest()->get();

        $annees = VisiteurRequest::where('superviseur_id', Auth::id())
            ->get()
            ->pluck('date_prevue')
            ->map(fn ($date) => $date->year)
            ->unique()
            ->sortDesc()
            ->values();

        return view('superviseur.demandes.archivages', compact('demandes', 'annees'));
    }
}
