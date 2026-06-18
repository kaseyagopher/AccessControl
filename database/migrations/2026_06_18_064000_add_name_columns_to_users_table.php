<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'firstName')) {
                $table->string('firstName')->nullable();
            }
            if (! Schema::hasColumn('users', 'lastName')) {
                $table->string('lastName')->nullable();
            }
            if (! Schema::hasColumn('users', 'matricule') && Schema::hasColumn('users', 'matricul')) {
                $table->string('matricule')->nullable();
            } elseif (! Schema::hasColumn('users', 'matricule')) {
                $table->string('matricule')->nullable();
            }
        });

        if (Schema::hasColumn('users', 'matricul') && Schema::hasColumn('users', 'matricule')) {
            DB::statement('UPDATE users SET matricule = matricul WHERE matricul IS NOT NULL');

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('matricul');
            });
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'firstName')) {
                $table->dropColumn('firstName');
            }
            if (Schema::hasColumn('users', 'lastName')) {
                $table->dropColumn('lastName');
            }
        });

        if (Schema::hasColumn('users', 'matricule') && ! Schema::hasColumn('users', 'matricul')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('matricul')->nullable();
            });

            DB::table('users')->whereNotNull('matricule')->update([
                'matricul' => DB::raw('matricule'),
            ]);

            Schema::table('users', function (Blueprint $table) {
                $table->dropColumn('matricule');
            });
        }
    }
};
