<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('tasks', function (Blueprint $table) {
        //     $table->increments('id');
        //     $table->string('title');
        //     $table->string('description');
        //     $table->string('assignee');
        //     $table->string('creator');
        //     $table->date('due_date');
        //     $table->string('status');
        //     $table->boolean('task_overdue')->default(0);
        //     $table->boolean('task_deleted')->default(0);
        //     $table->timestamps();
        // });
        Schema::create('tasks', function (Blueprint $table) {
            $table->integer('id')->autoIncrement();
            $table->string('title');
            $table->string('creator');
            $table->string('assignee');
            $table->string('status')->default('pending');
            $table->string('description');
            $table->dateTime('due_date');
            $table->boolean('task_deleted')->default(0);
            $table->timestamps();
            // $table->foreign('assignee')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}
