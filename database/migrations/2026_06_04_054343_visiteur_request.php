<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('visiteur_requests', function(Blueprint $table)
        {
            $table->id();
            $table->integer('visiteur_id');
            $table->string('name');
            $table->string('matricule_superviseur');
            $table->string('matricule_securite');
            $table->string('email');
            $table->string('motif');
            $table->string('statut_superviseur');
            $table->string('statut_securite');
            $table->DATE('date_visite');
            $table->DATETIME('heure_entree');
            $table->DATETIME('heure_sortie');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    
};
