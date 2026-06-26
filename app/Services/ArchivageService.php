<?php

namespace App\Services;

use App\Models\VisiteurRequest;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ArchivageService
{
    public static function query(Request $request, ?int $superviseurId = null): Builder
    {
        $query = VisiteurRequest::with(['visiteur', 'visiteurs', 'superviseur', 'service.departement'])
            ->where('statut', '!=', 'brouillon');

        if ($superviseurId !== null) {
            $query->where('superviseur_id', $superviseurId);
        }

        if ($request->filled('nom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('nom', 'like', '%'.$request->nom.'%'));
        }
        if ($request->filled('prenom')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('prenom', 'like', '%'.$request->prenom.'%'));
        }
        if ($request->filled('entreprise')) {
            $query->whereHas('visiteurs', fn ($q) => $q->where('entreprise', 'like', '%'.$request->entreprise.'%'));
        }
        if ($request->filled('superviseur')) {
            $query->whereHas('superviseur', fn ($q) => $q->where('name', 'like', '%'.$request->superviseur.'%'));
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

        return $query->latest();
    }

    public static function annees(?int $superviseurId = null): Collection
    {
        $query = VisiteurRequest::query();

        if ($superviseurId !== null) {
            $query->where('superviseur_id', $superviseurId);
        }

        return $query->get()
            ->pluck('date_prevue')
            ->map(fn ($date) => $date->year)
            ->unique()
            ->sortDesc()
            ->values();
    }
}
