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
        // Index notes — requête la plus fréquente du système 
        Schema::table('notes', function (Blueprint $table) { 
            $table->index(['etudiant_id', 'annee_acad_id', 'semestre'], 'idx_notes_calcul'); 
            $table->index(['element_constitutif_id', 'type_examen'], 'idx_notes_ec_type'); 
        }); 
    
        // Index résultats semestriels 
        Schema::table('resultat_semestres', function (Blueprint $table) { 
            $table->index(['etudiant_id', 'annee_acad_id', 'semestre'], 'idx_resultat_sem'); 
            $table->index(['annee_acad_id', 'semestre'], 'idx_resultat_annee_sem'); 
        }); 
    
        // Index résultats annuels 
        Schema::table('resultat_annuels', function (Blueprint $table) { 
            $table->index(['etudiant_id', 'annee_acad_id'], 'idx_resultat_annuel'); 
        }); 
    
        // Index étudiants — recherche fréquente 
        Schema::table('etudiants', function (Blueprint $table) { 
            $table->index(['specialite_id', 'niveau_actuel'], 'idx_etudiants_specialite'); 
            $table->index(['annee_acad_id'], 'idx_etudiants_annee'); 
        }); 
    
        // Index UE — calcul notes 
        Schema::table('unite_enseignements', function (Blueprint $table) { 
            $table->index(['specialite_id', 'semestre'], 'idx_ue_specialite_sem'); 
            $table->index(['specialite_id', 'type_ue'], 'idx_ue_type'); 
        }); 
    
        // Index EC 
        Schema::table('element_constitutifs', function (Blueprint $table) { 
            $table->index(['ue_id'], 'idx_ec_ue'); 
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('notes', function (Blueprint $table) { 
            $table->dropIndex('idx_notes_calcul'); 
            $table->dropIndex('idx_notes_ec_type'); 
        }); 
        Schema::table('resultat_semestres', function (Blueprint $table) { 
            $table->dropIndex('idx_resultat_sem'); 
            $table->dropIndex('idx_resultat_annee_sem'); 
        }); 
        Schema::table('resultat_annuels', function (Blueprint $table) { 
            $table->dropIndex('idx_resultat_annuel'); 
        }); 
        Schema::table('etudiants', function (Blueprint $table) { 
            $table->dropIndex('idx_etudiants_specialite'); 
            $table->dropIndex('idx_etudiants_annee'); 
        }); 
        Schema::table('unite_enseignements', function (Blueprint $table) { 
            $table->dropIndex('idx_ue_specialite_sem'); 
            $table->dropIndex('idx_ue_type'); 
        }); 
        Schema::table('element_constitutifs', function (Blueprint $table) { 
            $table->dropIndex('idx_ec_ue'); 
        }); 
    }
};
