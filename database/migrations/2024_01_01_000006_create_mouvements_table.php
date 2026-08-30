<?php
// database/migrations/2024_01_01_000006_create_mouvements_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materiel_id')->constrained('materiels')->onDelete('restrict');
            $table->enum('type', ['affectation', 'retour', 'transfert', 'ajout', 'archivage']);
            $table->integer('quantite')->default(1);
            $table->string('from_location')->nullable();
            $table->string('to_location')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('restrict');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};