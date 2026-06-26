<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('departements', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->timestamps();
        });

        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->foreignId('departement_id')->constrained('departements')->cascadeOnDelete();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('email');
            $table->string('fonction')->nullable()->after('matricule');
        });

        Schema::table('visiteurs', function (Blueprint $table) {
            $table->string('postnom')->nullable()->after('nom');
            $table->string('genre', 10)->nullable()->after('prenom');
        });

        Schema::table('visiteur_requests', function (Blueprint $table) {
            $table->foreignId('service_id')->nullable()->after('superviseur_id')->constrained('services')->nullOnDelete();
            $table->text('observation_acces')->nullable()->after('commentaire_securite');
            $table->text('observation_sortie')->nullable()->after('observation_acces');
        });
    }

    public function down(): void
    {
        Schema::table('visiteur_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_id');
            $table->dropColumn(['observation_acces', 'observation_sortie']);
        });

        Schema::table('visiteurs', function (Blueprint $table) {
            $table->dropColumn(['postnom', 'genre']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['username', 'fonction']);
        });

        Schema::dropIfExists('services');
        Schema::dropIfExists('departements');
    }
};
