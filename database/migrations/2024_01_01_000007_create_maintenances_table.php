<?php
// database/migrations/2024_01_01_000007_create_maintenances_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained('materiels')->onDelete('restrict');
            $table->enum('type', ['controle', 'reparation', 'intervention'])->default('controle');
            $table->enum('statut', ['planifie', 'en_cours', 'termine'])->default('planifie');
            $table->date('date_planifiee');
            $table->date('date_realisation')->nullable();
            $table->string('technicien')->nullable();
            $table->text('rapport')->nullable();
            $table->decimal('cout', 10, 2)->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};