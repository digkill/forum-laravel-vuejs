<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id();
            $table->text('body');
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                ->references('id')->on('users');
            $table->unsignedBigInteger('question_id')->unsigned(); // делаем внешний ключ;
            $table->foreign('question_id')           // в replies есть столбец QUESTION_ID
            ->references('id')->on('questions') // связь с QUESTIONS по ID
            ->onDelete('cascade');              // при удалении question'a - удалить каскадно и reply
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
