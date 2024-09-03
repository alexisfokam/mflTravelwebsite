
$table->text('description');<?php

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
        Schema::create('avis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('programme_id')->constrained()->onDelete('cascade');
            $table->integer('note')->unsigned(); // Note de 1 à 5
            $table->text('commentaire')->nullable();
            $table->timestamp('date_creation')->nullable();
            $table->string('statut')->default('en attente'); // Statut par défaut
            $table->timestamps();
            $table->engine = 'InnoDB';  // Utilisation du moteur InnoDB par défaut pour la gestion des clés étrangères
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avis');
    }
};
