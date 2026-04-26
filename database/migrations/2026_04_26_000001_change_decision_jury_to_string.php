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
        Schema::table('resultat_semestres', function (Blueprint $table) {
            $table->string('decision_jury', 50)->nullable()->change();
        });

        Schema::table('resultat_annuels', function (Blueprint $table) {
            $table->string('decision_jury', 50)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // En cas de retour arrière, on peut difficilement revenir à l'ENUM sans perdre des données accentuées,
        // donc on garde le type string ou on redéfinit l'enum original si nécessaire.
    }
};
