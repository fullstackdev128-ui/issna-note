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
        // Cette migration posait problème car elle tentait de supprimer un index unique
        // nécessaire à une contrainte (ou déjà présent avec un nom différent).
        // Comme la table est déjà créée correctement dans la migration précédente,
        // on neutralise celle-ci pour permettre la suite des migrations.
        
        /*
        Schema::table('resultat_annuels', function (Blueprint $table) {
            try {
                $table->dropUnique(['etudiant_id', 'annee_acad_id', 'niveau']);
            } catch (\Exception $e) {}

            if (!Schema::hasColumn('resultat_annuels', 'niveau')) {
                $table->integer('niveau')->after('annee_acad_id');
            }
        });

        Schema::table('resultat_annuels', function (Blueprint $table) {
            $table->unique(['etudiant_id', 'annee_acad_id', 'niveau'], 'unique_resultat_annuel');
        });
        */
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
        Schema::table('resultat_annuels', function (Blueprint $table) {
            $table->dropUnique('unique_resultat_annuel');
        });
        */
    }
};
