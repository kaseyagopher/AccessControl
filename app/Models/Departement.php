<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Departement extends Model
{
    protected $fillable = ['nom'];

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }
}
