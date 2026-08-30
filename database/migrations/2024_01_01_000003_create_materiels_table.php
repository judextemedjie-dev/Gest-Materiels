<?php
// database/migrations/2024_01_01_000003_create_materiels_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('materiels', function (Blueprint $table) {
            $table->id();
            $table->string('designation');
            $table->string('code_identification')->unique();
            $table->foreignId('categorie_id')->constrained('categories')->onDelete('restrict');
            $table->integer('quantite_stock')->default(0);
            $table->enum('statut', ['en_service', 'en_panne', 'en_reparation', 'archive'])->default('en_service');
            $table->string('localisation')->nullable();
            $table->text('description')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materiels');
    }
};