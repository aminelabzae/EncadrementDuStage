<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('users')->onDelete('cascade');
            $table->string('type'); // info, warning, success, error
            $table->string('titre');
            $table->text('message');
            $table->timestamp('lu_at')->nullable();
            $table->string('canal'); // email, sms, app
            $table->json('meta')->nullable();
            $table->timestamps();
            
            $table->index(['utilisateur_id', 'lu_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};