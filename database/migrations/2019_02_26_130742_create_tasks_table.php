<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{

    protected $table = 'tasks';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $getDefaultStatusForNewTask = function ($status) {
            return DB::table('task_statuses')
                ->where('name', '=', $status)
                ->first()
                ->pluck('id');
        };

        Schema::create($this->table, function (Blueprint $table) use ($getDefaultStatusForNewTask) {
            $table->increments('id');
            $table->string('name');
            $table->string('description')->nullable();
            $table->integer('status_id')->unsigned()->default($getDefaultStatusForNewTask('New'));
            $table->integer('creator_id')->unsigned();
            $table->integer('assignedTo_id')->unsigned();
            $table->timestamps();
            $table->foreign('status_id')->references('id')->on('task_statuses');
            $table->foreign('creator_id')->references('id')->on('users');
            $table->foreign('assignedTo_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists($this->table);
    }
}
