<?php

namespace App\Http\Controllers;

use App\Models\Lettre;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AgentLettreController extends Controller
{
    public function index()
    {
        $lettres = Lettre::with('superviseur')->latest()->get();

        return view('agent-de-security.lettres.index', compact('lettres'));
    }

    public function show($id)
    {
        $lettre = Lettre::with('superviseur')->findOrFail($id);

        if ($lettre->statut === 'envoyee') {
            $lettre->update(['statut' => 'recue']);
            NotificationService::notifierSuperviseur(
                $lettre->superviseur_id,
                'Votre lettre "'.$lettre->objet.'" a été reçue par la sécurité.',
                'lettre_recue'
            );
        }

        return view('agent-de-security.lettres.show', compact('lettre'));
    }

    public function valider(Request $request, $id)
    {
        $lettre = Lettre::findOrFail($id);

        if (! in_array($lettre->statut, ['envoyee', 'recue'])) {
            return redirect()->back()->with('error', 'Cette lettre a déjà été traitée.');
        }

        $request->validate(['observation_securite' => 'nullable|string|max:1000']);

        $lettre->update([
            'statut' => 'traitee',
            'observation_securite' => $request->observation_securite,
            'traite_par' => Auth::id(),
            'date_traitement' => now(),
        ]);

        return redirect()->route('agent.lettres.index')->with('success', 'Lettre traitée.');
    }

    public function refuser(Request $request, $id)
    {
        $lettre = Lettre::findOrFail($id);

        if (! in_array($lettre->statut, ['envoyee', 'recue'])) {
            return redirect()->back()->with('error', 'Cette lettre a déjà été traitée.');
        }

        $request->validate(['observation_securite' => 'required|string|max:1000'], [
            'observation_securite.required' => 'Veuillez indiquer le motif du refus.',
        ]);

        $lettre->update([
            'statut' => 'refusee',
            'observation_securite' => $request->observation_securite,
            'traite_par' => Auth::id(),
            'date_traitement' => now(),
        ]);

        return redirect()->route('agent.lettres.index')->with('success', 'Lettre refusée.');
    }
}
