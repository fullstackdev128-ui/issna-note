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
        Schema::create('etudiants', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 20)->unique();
            $table->string('nom', 100);
            $table->string('prenoms', 150);
            $table->date('date_naissance');
            $table->string('lieu_naissance', 100)->nullable();
            $table->enum('genre', ['M', 'F'])->nullable();
            $table->string('telephone', 20)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('lieu_residence', 150)->nullable();
            $table->string('etablissement_provenance', 200)->nullable();
            $table->string('nom_parent', 200)->nullable();
            $table->string('tel_parent', 20)->nullable();
            $table->foreignId('campus_id')->constrained('campus')->onDelete('restrict');
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('restrict');
            $table->integer('niveau_actuel');
            $table->foreignId('annee_acad_id')->constrained('annee_academiques')->onDelete('restrict');
            $table->date('date_inscription')->nullable();
            $table->enum('statut', ['actif', 'suspendu', 'diplome', 'abandonne'])->default('actif');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etudiants');
    }
};
