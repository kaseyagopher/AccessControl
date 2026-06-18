<?php

namespace App\Http\Controllers;

use App\Models\Lettre;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VisiteurRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $stats = [
            'superviseurs' => User::where('role', 'superviseur')->count(),
            'visiteurs_jour' => VisiteurRequest::whereDate('date_prevue', today())->count(),
            'en_attente' => VisiteurRequest::where('statut', 'en_attente')->count(),
            'validees' => VisiteurRequest::where('statut', 'valide')->count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function superviseur()
    {
        $userId = Auth::id();
        $stats = [
            'attendus' => VisiteurRequest::where('superviseur_id', $userId)
                ->whereDate('date_prevue', '>=', today())->whereIn('statut', ['en_attente', 'recu', 'valide'])->count(),
            'validees' => VisiteurRequest::where('superviseur_id', $userId)->where('statut', 'valide')->count(),
            'refusees' => VisiteurRequest::where('superviseur_id', $userId)->where('statut', 'refuse')->count(),
        ];
        $notifications = UserNotification::where('user_id', $userId)->latest()->take(10)->get();

        return view('superviseur.dashboard', compact('stats', 'notifications'));
    }

    public function agent()
    {
        $stats = [
            'aujourdhui' => VisiteurRequest::whereDate('date_prevue', today())->where('statut', 'valide')->count(),
            'a_traiter' => VisiteurRequest::whereIn('statut', ['en_attente', 'recu'])->count(),
            'lettres' => Lettre::whereIn('statut', ['envoyee', 'recue'])->count(),
        ];
        $notifications = UserNotification::where('user_id', Auth::id())->latest()->take(10)->get();

        return view('agent-de-security.dashboard', compact('stats', 'notifications'));
    }

    public function marquerLu($id)
    {
        $notif = UserNotification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['lu' => true]);

        return redirect()->back();
    }
}
