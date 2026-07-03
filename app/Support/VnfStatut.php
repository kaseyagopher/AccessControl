<?php

namespace App\Support;

class VnfStatut
{
    public static function mapFiltre(string $filtre): string|array
    {
        return match ($filtre) {
            'valide' => 'valide',
            'en_attente' => ['brouillon', 'en_attente', 'recu'],
            'non_valide' => 'refuse',
            'termine' => 'termine',
            'expiree' => 'expiree',
            default => $filtre,
        };
    }

    public static function libellesFiltre(): array
    {
        return [
            'en_attente' => 'En attente',
            'valide' => 'Validé',
            'non_valide' => 'Non validé',
            'termine' => 'Terminé',
            'expiree' => 'Expiré',
        ];
    }
}
