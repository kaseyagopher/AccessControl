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
            ->get();

        foreach ($demandes as $demande) {
            $demande->update(['statut' => 'expiree']);

            NotificationService::notifierSuperviseur(
                $demande->superviseur_id,
                'La visite prévue le '.$demande->date_prevue->format('d/m/Y').' a expiré (visiteur non présent).',
                'visite_expiree'
            );
        }
    }
}
