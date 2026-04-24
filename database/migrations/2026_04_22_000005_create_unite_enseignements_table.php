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
        Schema::create('unite_enseignements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('specialite_id')->constrained('specialites')->onDelete('restrict');
            $table->string('code_ue', 20)->nullable();
            $table->string('nom', 100);
            $table->enum('type_ue', ['Fondamentale', 'Professionnelle', 'Transversale']);
            $table->integer('niveau'); // 1, 2, 3...
            $table->integer('semestre'); // 1, 2, 3...
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unite_enseignements');
    }
};
