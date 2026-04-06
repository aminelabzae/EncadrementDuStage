<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('entreprise_id')->constrained()->onDelete('cascade');
            $table->foreignId('stagiaire_id')->constrained()->onDelete('cascade');
            $table->string('type'); // PFE, stage d'été, etc.
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->text('sujet');
            $table->string('statut')->default('en_cours'); // en_cours, termine, annule, suspendu
            $table->foreignId('encadrant_id')->nullable()->constrained('encadrants')->onDelete('set null');
            $table->timestamps();
            
            // Index for performance
            $table->index(['date_debut', 'date_fin']);
            $table->index('statut');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stages');
    }
};