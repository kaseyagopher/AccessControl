<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VisiteurRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RapportController extends Controller
{
    public function index(Request $request)
    {
        $debut = $request->input('debut', now()->startOfMonth()->toDateString());
        $fin = $request->input('fin', now()->toDateString());

        $stats = [
            'total_visiteurs' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])->count(),
            'validees' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])->where('statut', 'valide')->count(),
            'refusees' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])->where('statut', 'refuse')->count(),
            'terminees' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])->where('statut', 'termine')->count(),
            'expirees' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])->where('statut', 'expiree')->count(),
            'par_superviseur' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])
                ->select('superviseur_id', DB::raw('count(*) as total'))
                ->groupBy('superviseur_id')
                ->with('superviseur')
                ->get(),
            'entreprises' => VisiteurRequest::whereBetween('date_prevue', [$debut, $fin])
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

        $demandes = VisiteurRequest::with(['visiteur', 'superviseur', 'validateur'])
            ->whereBetween('date_prevue', [$debut, $fin])
            ->get();

        $filename = 'rapport_visites_'.date('Y-m-d').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($demandes) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Visiteur', 'Entreprise', 'Superviseur', 'Date', 'Statut', 'Agent validateur', 'Motif']);
            foreach ($demandes as $d) {
                fputcsv($file, [
                    $d->visiteur->nomComplet(),
                    $d->visiteur->entreprise,
                    $d->superviseur->name,
                    $d->date_prevue->format('Y-m-d'),
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

        $demandes = VisiteurRequest::with(['visiteur', 'superviseur', 'validateur'])
            ->whereBetween('date_prevue', [$debut, $fin])
            ->get();

        return view('admin.rapport-pdf', compact('demandes', 'debut', 'fin'));
    }
}
