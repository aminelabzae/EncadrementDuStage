<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('incident_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->text('description');
            $table->date('date');
            $table->string('gravite'); // faible, moyenne, elevee, critique
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('incident_stages');
    }
};