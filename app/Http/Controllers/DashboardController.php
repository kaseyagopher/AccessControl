<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserNotification;
use App\Models\VisiteurRequest;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function admin()
    {
        $today = today()->toDateString();
        $userStats = User::query()
            ->selectRaw("SUM(CASE WHEN role = 'superviseur' THEN 1 ELSE 0 END) as superviseurs")
            ->selectRaw("SUM(CASE WHEN role = 'agent-de-security' THEN 1 ELSE 0 END) as agents")
            ->first();
        $vr = VisiteurRequest::query()
            ->selectRaw('SUM(CASE WHEN date(date_prevue) = ? THEN 1 ELSE 0 END) as visiteurs_jour', [$today])
            ->selectRaw("SUM(CASE WHEN statut = 'en_attente' THEN 1 ELSE 0 END) as en_attente")
            ->selectRaw("SUM(CASE WHEN statut = 'valide' THEN 1 ELSE 0 END) as validees")
            ->selectRaw("SUM(CASE WHEN statut = 'refuse' THEN 1 ELSE 0 END) as refusees")
            ->selectRaw("SUM(CASE WHEN statut = 'termine' THEN 1 ELSE 0 END) as terminees")
            ->selectRaw("SUM(CASE WHEN statut = 'expiree' THEN 1 ELSE 0 END) as expirees")
            ->selectRaw('SUM(CASE WHEN heure_arrivee IS NOT NULL AND date(heure_arrivee) = ? THEN 1 ELSE 0 END) as entrees_jour', [$today])
            ->selectRaw('SUM(CASE WHEN heure_sortie IS NOT NULL AND date(heure_sortie) = ? THEN 1 ELSE 0 END) as sorties_jour', [$today])
            ->first();

        $stats = [
            'superviseurs' => (int) $userStats->superviseurs,
            'agents' => (int) $userStats->agents,
            'visiteurs_jour' => (int) $vr->visiteurs_jour,
            'en_attente' => (int) $vr->en_attente,
            'validees' => (int) $vr->validees,
            'refusees' => (int) $vr->refusees,
            'terminees' => (int) $vr->terminees,
            'expirees' => (int) $vr->expirees,
            'entrees_jour' => (int) $vr->entrees_jour,
            'sorties_jour' => (int) $vr->sorties_jour,
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function superviseur()
    {
        $userId = Auth::id();
        $today = today()->toDateString();
        $vr = VisiteurRequest::where('superviseur_id', $userId)
            ->selectRaw('SUM(CASE WHEN date(date_prevue) >= ? AND statut IN (\'en_attente\', \'recu\', \'valide\') THEN 1 ELSE 0 END) as attendus', [$today])
            ->selectRaw("SUM(CASE WHEN statut IN ('en_attente', 'recu') THEN 1 ELSE 0 END) as en_attente")
            ->selectRaw("SUM(CASE WHEN statut = 'valide' THEN 1 ELSE 0 END) as validees")
            ->selectRaw("SUM(CASE WHEN statut = 'refuse' THEN 1 ELSE 0 END) as refusees")
            ->selectRaw("SUM(CASE WHEN statut = 'termine' THEN 1 ELSE 0 END) as terminees")
            ->selectRaw("SUM(CASE WHEN statut = 'expiree' THEN 1 ELSE 0 END) as expirees")
            ->first();

        $stats = [
            'attendus' => (int) $vr->attendus,
            'en_attente' => (int) $vr->en_attente,
            'validees' => (int) $vr->validees,
            'refusees' => (int) $vr->refusees,
            'terminees' => (int) $vr->terminees,
            'expirees' => (int) $vr->expirees,
        ];
        $notifications = UserNotification::where('user_id', $userId)->latest()->take(10)->get();
        $recentDemandes = VisiteurRequest::with(['visiteur', 'visiteurs', 'service'])
            ->where('superviseur_id', $userId)
            ->where('statut', '!=', 'brouillon')
            ->latest()
            ->take(5)
            ->get();

        return view('superviseur.dashboard', compact('stats', 'notifications', 'recentDemandes'));
    }

    public function agent()
    {
        $today = today()->toDateString();
        $vr = VisiteurRequest::query()
            ->selectRaw("SUM(CASE WHEN (statut = 'valide' OR statut = 'termine') AND date(date_prevue) = ? THEN 1 ELSE 0 END) as aujourdhui", [$today])
            ->selectRaw("SUM(CASE WHEN statut IN ('en_attente', 'recu') THEN 1 ELSE 0 END) as a_traiter")
            ->selectRaw('SUM(CASE WHEN heure_arrivee IS NOT NULL AND date(heure_arrivee) = ? THEN 1 ELSE 0 END) as entrees_jour', [$today])
            ->selectRaw('SUM(CASE WHEN heure_sortie IS NOT NULL AND date(heure_sortie) = ? THEN 1 ELSE 0 END) as sorties_jour', [$today])
            ->first();

        $stats = [
            'aujourdhui' => (int) $vr->aujourdhui,
            'a_traiter' => (int) $vr->a_traiter,
            'entrees_jour' => (int) $vr->entrees_jour,
            'sorties_jour' => (int) $vr->sorties_jour,
        ];
        $notifications = UserNotification::where('user_id', Auth::id())->latest()->take(10)->get();

        return view('agent-de-security.dashboard', compact('stats', 'notifications'));
    }

    public function notifications()
    {
        $role = Auth::user()->role;
        $markReadRoute = match ($role) {
            'superviseur' => 'superviseur.notifications.lu',
            'agent-de-security' => 'agent.notifications.lu',
            default => abort(403),
        };

        $notifications = UserNotification::where('user_id', Auth::id())
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications', 'markReadRoute'));
    }

    public function marquerLu($id)
    {
        $notif = UserNotification::where('user_id', Auth::id())->findOrFail($id);
        $notif->update(['lu' => true]);
        UserNotification::invalidateUnreadCache(Auth::id());

        return redirect()->back();
    }
}
