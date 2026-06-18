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
        $query = VisiteurRequest::with('visiteur')
            ->where('superviseur_id', Auth::id());

        if ($request->filled('nom')) {
            $query->whereHas('visiteur', fn ($q) => $q->where('nom', 'like', '%'.$request->nom.'%'));
        }
        if ($request->filled('entreprise')) {
            $query->whereHas('visiteur', fn ($q) => $q->where('entreprise', 'like', '%'.$request->entreprise.'%'));
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
            'nom' => 'required|string|max:100',
            'prenom' => 'required|string|max:100',
            'telephone' => 'required|string|max:30',
            'entreprise' => 'required|string|max:150',
            'fonction' => 'nullable|string|max:100',
            'motif' => 'required|string|max:2000',
            'date_prevue' => 'required|date|after_or_equal:today',
            'heure_prevue' => 'required|string',
            'nombre_visiteurs' => 'required|integer|min:1|max:50',
            'document' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'date_prevue.after_or_equal' => 'La date de visite ne peut pas être dans le passé.',
            'document.mimes' => 'Le document doit être un PDF, Word ou une image.',
            'document.max' => 'Le document ne doit pas dépasser 5 Mo.',
        ]);

        $visiteur = Visiteur::create([
            'nom' => $data['nom'],
            'prenom' => $data['prenom'],
            'telephone' => $data['telephone'],
            'entreprise' => $data['entreprise'],
            'fonction' => $data['fonction'] ?? null,
            'superviseur_id' => Auth::id(),
        ]);

        $documentPath = null;
        if ($request->hasFile('document')) {
            $documentPath = $request->file('document')->store('documents', 'public');
        }

        VisiteurRequest::create([
            'visiteur_id' => $visiteur->id,
            'superviseur_id' => Auth::id(),
            'motif' => $data['motif'],
            'date_prevue' => $data['date_prevue'],
            'heure_prevue' => $data['heure_prevue'],
            'nombre_visiteurs' => $data['nombre_visiteurs'],
            'document_path' => $documentPath,
            'statut' => 'en_attente',
        ]);

        NotificationService::notifierSecurite(
            'Nouvelle demande de visite de '.Auth::user()->name,
            'nouvelle_demande'
        );

        return redirect()->route('superviseur.demandes.index')
            ->with('success', 'Demande de visite envoyée à la sécurité.');
    }

    public function historique()
    {
        $demandes = VisiteurRequest::with('visiteur')
            ->where('superviseur_id', Auth::id())
            ->latest()
            ->get();

        return view('superviseur.demandes.historique', compact('demandes'));
    }
}
