<?php
// database/migrations/2024_01_10_000002_create_signalements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('signalements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('affectation_id')->constrained('affectations')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('materiel_id')->constrained('materiels')->onDelete('cascade');
            $table->enum('type', ['panne', 'deterioration', 'perte', 'autre'])->default('autre');
            $table->text('description');
            $table->enum('statut', ['nouveau', 'lu', 'traite'])->default('nouveau');
            $table->timestamp('lu_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signalements');
    }
};