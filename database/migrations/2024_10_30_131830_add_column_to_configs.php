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
        Schema::table('configs', function (Blueprint $table) {
            //
           // $table->text('titre_annee')->nullable();
         //   $table->text('titre_prix')->nullable();
           // $table->text('titre_satisfaction')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            //
          //  $table->dropColumn('titre_annee');
          //  $table->dropColumn('titre_prix');
           // $table->dropColumn('titre_satisfaction');
        });
    }
};
