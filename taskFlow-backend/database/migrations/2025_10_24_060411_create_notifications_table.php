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
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'task_assigned', 'comment_added', 'task_status_changed'
            $table->string('title');
            $table->text('message');
            $table->json('data')->nullable(); // JSON con info adicional (task_id, project_id, etc)
            $table->boolean('read')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('notifications');
    }
};