<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
            $table->foreignId('class_info_id')->constrained('class_infos')->onDelete('cascade');
            $table->string('name');
            $table->string('color')->default('#3b82f6');
            $table->dateTime('due_date')->nullable();
            $table->dateTime('start_date')->nullable();
            $table->enum('status', ['pending', 'completed'])->default('pending');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};