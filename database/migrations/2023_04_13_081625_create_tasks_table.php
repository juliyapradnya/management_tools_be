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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->integer('id_project');
            $table->integer('id_employee');
            $table->integer('id_department');
            $table->integer('id_ticket');
            $table->string('task_name');
            $table->string('task_id');
            $table->string('task_type');
            $table->text('task_depedence_id');
            $table->string('description');
            $table->date('start_date');
            $table->date('due_date');
            $table->string('status_task');
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
        Schema::dropIfExists('tasks');
    }
}
