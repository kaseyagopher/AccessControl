<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visiteur extends Model
{
    protected $fillable = [
        'nom', 'prenom', 'telephone', 'entreprise', 'fonction', 'superviseur_id',
    ];

    public function superviseur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'superviseur_id');
    }

    public function demandes(): HasMany
    {
        return $this->hasMany(VisiteurRequest::class);
    }
}
