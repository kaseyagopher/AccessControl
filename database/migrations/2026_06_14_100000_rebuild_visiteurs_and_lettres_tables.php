<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::dropIfExists('visiteur_requests');
        Schema::dropIfExists('visiteurs');
        Schema::dropIfExists('lettres');
        Schema::dropIfExists('user_notifications');

        Schema::create('visiteurs', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('telephone');
            $table->string('entreprise');
            $table->string('fonction')->nullable();
            $table->foreignId('superviseur_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::create('visiteur_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visiteur_id')->constrained('visiteurs')->cascadeOnDelete();
            $table->foreignId('superviseur_id')->constrained('users')->cascadeOnDelete();
            $table->text('motif');
            $table->date('date_prevue');
            $table->string('heure_prevue');
            $table->unsignedInteger('nombre_visiteurs')->default(1);
            $table->string('document_path')->nullable();
            $table->string('statut')->default('en_attente');
            $table->text('commentaire_securite')->nullable();
            $table->foreignId('valide_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('heure_arrivee')->nullable();
            $table->timestamp('heure_sortie')->nullable();
            $table->timestamps();
        });

        Schema::create('lettres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('superviseur_id')->constrained('users')->cascadeOnDelete();
            $table->string('objet');
            $table->text('commentaire')->nullable();
            $table->string('fichier_path')->nullable();
            $table->string('statut')->default('envoyee');
            $table->text('observation_securite')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_traitement')->nullable();
            $table->timestamps();
        });

        Schema::create('user_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('message');
            $table->string('type')->nullable();
            $table->boolean('lu')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_notifications');
        Schema::dropIfExists('lettres');
        Schema::dropIfExists('visiteur_requests');
        Schema::dropIfExists('visiteurs');
    }
};
