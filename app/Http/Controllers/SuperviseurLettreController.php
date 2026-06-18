<?php

namespace App\Http\Controllers;

use App\Models\Lettre;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuperviseurLettreController extends Controller
{
    public function index()
    {
        $lettres = Lettre::where('superviseur_id', Auth::id())->latest()->get();

        return view('superviseur.lettres.index', compact('lettres'));
    }

    public function create()
    {
        return view('superviseur.lettres.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'objet' => 'required|string|max:200',
            'commentaire' => 'nullable|string|max:2000',
            'fichier' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120',
        ], [
            'fichier.mimes' => 'Le fichier doit être un PDF, Word ou une image.',
            'fichier.max' => 'Le fichier ne doit pas dépasser 5 Mo.',
        ]);

        $fichierPath = null;
        if ($request->hasFile('fichier')) {
            $fichierPath = $request->file('fichier')->store('lettres', 'public');
        }

        Lettre::create([
            'superviseur_id' => Auth::id(),
            'objet' => $data['objet'],
            'commentaire' => $data['commentaire'] ?? null,
            'fichier_path' => $fichierPath,
            'statut' => 'envoyee',
        ]);

        NotificationService::notifierSecurite(
            'Nouvelle lettre reçue de '.Auth::user()->name,
            'nouvelle_lettre'
        );

        return redirect()->route('superviseur.lettres.index')
            ->with('success', 'Lettre envoyée à la sécurité.');
    }
}
