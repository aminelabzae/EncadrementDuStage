<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('presence_stages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stage_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->boolean('present')->default(true);
            
            // No timestamps as specified in schema
            $table->unique(['stage_id', 'date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('presence_stages');
    }
};