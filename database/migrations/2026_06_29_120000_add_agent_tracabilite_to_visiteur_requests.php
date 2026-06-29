<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('visiteur_requests', function (Blueprint $table) {
            $table->foreignId('arrivee_par')->nullable()->after('heure_arrivee')->constrained('users')->nullOnDelete();
            $table->foreignId('sortie_par')->nullable()->after('heure_sortie')->constrained('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('visiteur_requests', function (Blueprint $table) {
            $table->dropConstrainedForeignId('arrivee_par');
            $table->dropConstrainedForeignId('sortie_par');
        });
    }
};
