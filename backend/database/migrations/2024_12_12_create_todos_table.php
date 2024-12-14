<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('todos')) {
            Schema::create('todos', function (Blueprint $table) {
                $table->id();
                $table->smallInteger('owner_id')->nullable();
                $table->string('title');
                $table->text('description');
                $table->dateTime('due_date');
                $table->string('priority');
                $table->string('image')->nullable();
                $table->boolean('is_completed')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('todos');
    }
};
