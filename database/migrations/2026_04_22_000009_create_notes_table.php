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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('restrict');
            $table->foreignId('element_constitutif_id')->constrained('element_constitutifs')->onDelete('restrict');
            $table->foreignId('annee_acad_id')->constrained('annee_academiques')->onDelete('restrict');
            $table->integer('semestre');
            $table->enum('type_examen', ['CC', 'SN', 'RP']);
            $table->decimal('valeur', 5, 2);
            $table->dateTime('date_saisie')->useCurrent();
            $table->foreignId('saisi_par')->constrained('users')->onDelete('restrict');
            $table->foreignId('modifie_par')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('date_modification')->nullable();
            $table->text('motif_modification')->nullable(); // Traçabilité
            $table->timestamps();
            // Contrainte unicité : un étudiant ne peut avoir qu'une note CC, SN ou RP par EC par semestre par année
            $table->unique(['etudiant_id', 'element_constitutif_id', 'annee_acad_id', 'semestre', 'type_examen'], 'note_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
