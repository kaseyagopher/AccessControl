<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Lettre extends Model
{
    public const STATUTS = ['envoyee', 'recue', 'traitee', 'refusee'];

    protected $fillable = [
        'superviseur_id', 'objet', 'commentaire', 'fichier_path', 'statut',
        'observation_securite', 'traite_par', 'date_traitement',
    ];

    protected function casts(): array
    {
        return [
            'date_traitement' => 'datetime',
        ];
    }

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'traite_par');
    }
}
