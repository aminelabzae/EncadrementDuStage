<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('piece_jointes', function (Blueprint $table) {
            $table->id();
            $table->string('objet_type'); 
            $table->unsignedBigInteger('objet_id');
            $table->string('nom_fichier');
            $table->string('chemin');
            $table->string('mime');
            $table->unsignedBigInteger('taille');
            $table->foreignId('uploaded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            
            
            $table->index(['objet_type', 'objet_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('piece_jointes');
    }
};

