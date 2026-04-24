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
        Schema::create('resultat_annuels', function (Blueprint $table) { 
            $table->id(); 
            $table->foreignId('etudiant_id')->constrained('etudiants')->onDelete('cascade'); 
            $table->foreignId('annee_acad_id')->constrained('annee_academiques')->onDelete('cascade'); 
            $table->integer('niveau'); // 1, 2, 3... 
            $table->decimal('moyenne_s1', 5, 2)->nullable(); 
            $table->decimal('moyenne_s2', 5, 2)->nullable(); 
            $table->decimal('moyenne_annuelle', 5, 2)->nullable(); 
            $table->integer('credits_valides_s1')->default(0); 
            $table->integer('credits_valides_s2')->default(0); 
            $table->integer('credits_valides_total')->default(0); 
            $table->integer('total_credits')->default(60); 
            $table->decimal('mgp_annuel', 4, 2)->nullable(); 
            $table->string('grade_annuel', 5)->nullable(); 
            $table->string('mention_annuelle', 30)->nullable(); 
            $table->enum('decision_jury', ['Admis(e)', 'Ajourné(e)', 'Autorisé(e) à continuer', 'Exclu(e)'])->nullable(); 
            $table->foreignId('valide_par')->nullable()->constrained('users'); 
            $table->datetime('date_calcul')->nullable(); 
            $table->timestamps(); 
            $table->unique(['etudiant_id', 'annee_acad_id', 'niveau']); 
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resultat_annuels');
    }
};
