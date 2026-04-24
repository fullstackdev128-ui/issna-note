<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('specialites', function (Blueprint $table) {
            $table->enum('type_diplome', ['BTS', 'Licence', 'Master1', 'Master2'])
                  ->default('BTS')
                  ->after('duree_ans');
        });
    }

    public function down(): void
    {
        Schema::table('specialites', function (Blueprint $table) {
            $table->dropColumn('type_diplome');
        });
    }
};
