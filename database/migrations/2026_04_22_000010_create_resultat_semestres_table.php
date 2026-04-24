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
        Schema::create('resultat_semestres', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('restrict');
            $table->foreignId('annee_acad_id')->constrained('annee_academiques')->onDelete('restrict');
            $table->integer('semestre');
            $table->integer('total_credits')->nullable();
            $table->integer('credits_valides')->nullable();
            $table->decimal('moyenne_sem', 5, 2)->nullable();
            $table->decimal('mgp', 4, 2)->nullable();
            $table->string('grade', 5)->nullable();
            $table->string('mention', 30)->nullable();
            $table->enum('decision_jury', ['Admis', 'Ajourne', 'Autorise a continuer', 'Exclu'])->nullable();
            $table->dateTime('date_calcul')->nullable();
            $table->boolean('valide')->default(false); // Verrou post-validation
            $table->timestamps();
            $table->unique(['etudiant_id', 'annee_acad_id', 'semestre'], 'resultat_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_semestres');
    }
};
