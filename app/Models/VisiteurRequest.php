<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class VisiteurRequest extends Model
{
    public const STATUTS = ['brouillon', 'en_attente', 'recu', 'valide', 'refuse', 'termine', 'expiree'];

    protected $fillable = [
        'visiteur_id', 'superviseur_id', 'service_id', 'motif', 'date_prevue', 'heure_prevue',
        'nombre_visiteurs', 'document_path', 'statut', 'commentaire_securite',
        'observation_acces', 'observation_sortie',
        'valide_par', 'date_validation', 'heure_arrivee', 'arrivee_par', 'heure_sortie', 'sortie_par',
    ];

    public const RELATIONS_AUDIT = ['validateur', 'agentArrivee', 'agentSortie'];

    protected function casts(): array
    {
        return [
            'date_prevue' => 'date',
            'date_validation' => 'datetime',
            'heure_arrivee' => 'datetime',
            'heure_sortie' => 'datetime',
        ];
    }

    public function scopePourAccesSortie($query)
    {
        return $query->where(function ($q) {
            $q->where('statut', 'valide')
                ->orWhere(function ($q2) {
                    $q2->where('statut', 'termine')->whereDate('date_prevue', today());
                });
        });
    }

    public function estVisiteAVenir(): bool
    {
        return $this->statut === 'valide' && $this->date_prevue->isAfter(today());
    }

    public function peutEnregistrerAcces(): bool
    {
        return $this->statut === 'valide'
            && ! $this->heure_arrivee
            && $this->date_prevue->lte(today());
    }

    public function peutEnregistrerSortie(): bool
    {
        return $this->heure_arrivee
            && ! $this->heure_sortie
            && in_array($this->statut, ['valide', 'termine'], true);
    }

    public function visiteur(): BelongsTo
    {
        return $this->belongsTo(Visiteur::class);
    }

    public function visiteurPrincipal(): ?Visiteur
    {
        if ($this->relationLoaded('visiteurs') && $this->visiteurs->isNotEmpty()) {
            return $this->visiteurs->first();
        }

        return $this->visiteur;
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

    public function agentArrivee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'arrivee_par');
    }

    public function agentSortie(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sortie_par');
    }
}
