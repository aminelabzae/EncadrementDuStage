<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('journal_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->text('activites');
            $table->decimal('heures', 5, 2)->nullable(); 
            $table->timestamps();
            
            
            $table->unique(['stage_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('journal_stages');
    }
};