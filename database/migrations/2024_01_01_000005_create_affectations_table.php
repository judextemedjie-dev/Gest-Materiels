<?php
// database/migrations/2024_01_01_000005_create_affectations_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('affectations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained('materiels')->onDelete('restrict');
            $table->foreignId('client_id')->constrained('clients')->onDelete('restrict');
            $table->foreignId('gestionnaire_id')->constrained('users')->onDelete('restrict');
            $table->integer('quantite');
            $table->date('date_affectation');
            $table->time('heure_affectation');
            $table->enum('statut', ['affecte', 'restitue'])->default('affecte');
            $table->date('date_restitution')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('affectations');
    }
};