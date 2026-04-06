<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluation_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->decimal('note', 5, 2)->nullable();
            $table->text('commentaire')->nullable();
            $table->date('date_evaluation');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_stages');
    }
};