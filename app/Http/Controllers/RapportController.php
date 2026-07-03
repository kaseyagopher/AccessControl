<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VisiteurRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $debut = $request->input('debut', now()->startOfMonth()->toDateString());
        $fin = $request->input('fin', now()->toDateString());

        $baseQuery = $this->rapportQuery($debut, $fin);

        $stats = [
            'total_visiteurs' => (clone $baseQuery)->count(),
            'soumises_periode' => VisiteurRequest::whereDate('created_at', '>=', $debut)
                ->whereDate('created_at', '<=', $fin)->count(),
            'soumises_jour' => VisiteurRequest::whereDate('created_at', today())->count(),
            'validees' => (clone $baseQuery)->where('statut', 'valide')->count(),
            'refusees' => (clone $baseQuery)->where('statut', 'refuse')->count(),
            'terminees' => (clone $baseQuery)->where('statut', 'termine')->count(),
            'expirees' => (clone $baseQuery)->where('statut', 'expiree')->count(),
            'par_superviseur' => (clone $baseQuery)
                ->select('superviseur_id', DB::raw('count(*) as total'))
                ->groupBy('superviseur_id')
                ->with('superviseur')
                ->get(),
            'entreprises' => (clone $baseQuery)
                ->join('visiteurs', 'visiteur_requests.visiteur_id', '=', 'visiteurs.id')
                ->select('visiteurs.entreprise', DB::raw('count(*) as total'))
                ->groupBy('visiteurs.entreprise')
                ->orderByDesc('total')
                ->limit(10)
                ->get(),
        ];

        return view('admin.rapports', compact('stats', 'debut', 'fin'));
    }

    public function exportExcel(Request $request)
    {
        $debut = $request->input('debut', now()->startOfMonth()->toDateString());
        $fin = $request->input('fin', now()->toDateString());

        $demandes = $this->rapportQuery($debut, $fin)
            ->with(['visiteur', 'superviseur', 'validateur'])
            ->get();

        $filename = 'rapport_visites_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($demandes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Visiteur', 'Entreprise', 'Superviseur', 'Date visite', 'Date soumission', 'Statut', 'Agent validateur', 'Motif']);
            foreach ($demandes as $d) {
                fputcsv($file, [
                    $d->visiteur->nomComplet(),
                    $d->visiteur->entreprise,
                    $d->superviseur->name,
                    $d->date_prevue->format('Y-m-d'),
                    $d->created_at->format('Y-m-d H:i'),
                    $d->statut,
                    $d->validateur?->nomComplet() ?? '—',
                    $d->motif,
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        $debut = $request->input('debut', now()->startOfMonth()->toDateString());
        $fin = $request->input('fin', now()->toDateString());

        $demandes = $this->rapportQuery($debut, $fin)
            ->with(['visiteur', 'superviseur', 'validateur'])
            ->get();

        return view('admin.rapport-pdf', compact('demandes', 'debut', 'fin'));
    }

    private function rapportQuery(string $debut, string $fin): Builder
    {
        return VisiteurRequest::query()
            ->where(function ($query) use ($debut, $fin) {
                $query->whereBetween('date_prevue', [$debut, $fin])
                    ->orWhere(function ($q) use ($debut, $fin) {
                        $q->whereDate('created_at', '>=', $debut)
                            ->whereDate('created_at', '<=', $fin);
                    });
            });
    }
}
