<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VisiteurRequest extends Model
{
    public const STATUTS = ['brouillon', 'en_attente', 'recu', 'valide', 'refuse', 'termine'];

    protected $fillable = [
        'visiteur_id', 'superviseur_id', 'service_id', 'motif', 'date_prevue', 'heure_prevue',
        'nombre_visiteurs', 'document_path', 'statut', 'commentaire_securite',
        'observation_acces', 'observation_sortie',
        'valide_par', 'date_validation', 'heure_arrivee', 'heure_sortie',
    ];

    protected function casts(): array
    {
        return [
            'date_prevue' => 'date',
            'date_validation' => 'datetime',
            'heure_arrivee' => 'datetime',
            'heure_sortie' => 'datetime',
        ];
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visiteur::class);
    }

    public function visiteurs(): BelongsToMany
    {
        return $this->belongsToMany(Visiteur::class, 'visiteur_request_visiteur');
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function isEditableBySuperviseur(): bool
    {
        return in_array($this->statut, ['brouillon', 'en_attente'], true);
    }

    public function validateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'valide_par');
    }
}
