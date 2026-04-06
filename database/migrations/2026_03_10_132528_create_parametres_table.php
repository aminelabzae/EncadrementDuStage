<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('parametres', function (Blueprint $table) {
            $table->id();
            $table->string('cle')->unique();
            $table->text('valeur');
            $table->string('groupe')->default('general');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('groupe');
        });
    }

    public function down()
    {
        Schema::dropIfExists('parametres');
    }
};