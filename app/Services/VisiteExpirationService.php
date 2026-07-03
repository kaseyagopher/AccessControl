<?php

namespace App\Services;

use App\Models\VisiteurRequest;

class VisiteExpirationService
{
    public static function expirer(): void
    {
        $demandes = VisiteurRequest::query()
            ->where('statut', 'valide')
            ->whereDate('date_prevue', '<', today())
            ->whereNull('heure_arrivee')
            ->get(['id', 'superviseur_id', 'date_prevue']);

        if ($demandes->isEmpty()) {
            return;
        }

        VisiteurRequest::whereIn('id', $demandes->pluck('id'))
            ->update(['statut' => 'expiree']);

        foreach ($demandes as $demande) {
            NotificationService::notifierSuperviseur(
                $demande->superviseur_id,
                'La visite prévue le '.$demande->date_prevue->format('d/m/Y').' a expiré (visiteur non présent).',
                'visite_expiree'
            );
        }
    }
}
