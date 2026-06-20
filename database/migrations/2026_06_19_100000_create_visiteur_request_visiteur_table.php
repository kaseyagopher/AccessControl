<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visiteur_request_visiteur', function (Blueprint $table) {
            $table->id();
            $table->foreignId('visiteur_request_id')->constrained('visiteur_requests')->cascadeOnDelete();
            $table->foreignId('visiteur_id')->constrained('visiteurs')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['visiteur_request_id', 'visiteur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visiteur_request_visiteur');
    }
};
