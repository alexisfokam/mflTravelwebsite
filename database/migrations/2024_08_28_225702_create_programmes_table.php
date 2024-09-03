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
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pays_id')->constrained()->onDelete('cascade');
            $table->string('titre');
            $table->string('type');
            $table->text('description');
            $table->json('conditions');
            $table->float('frais');
            $table->string('duree');
            $table->date('delais');
            $table->string('statut');
            $table->integer('nbr_place');
            $table->timestamps();
            $table->engine = 'InnoDB'; 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
