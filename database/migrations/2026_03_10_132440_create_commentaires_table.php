<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('commentaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->string('objet_type'); // Stage, JournalStage, etc.
            $table->unsignedBigInteger('objet_id');
            $table->text('contenu');
            $table->timestamps();
            $table->softDeletes();
            
            
            $table->index(['objet_type', 'objet_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaires');
    }
};