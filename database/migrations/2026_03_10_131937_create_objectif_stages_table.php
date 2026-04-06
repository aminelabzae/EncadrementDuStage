<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('objectif_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->string('libelle');
            $table->string('statut')->default('en_attente'); 
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('objectif_stages');
    }
};