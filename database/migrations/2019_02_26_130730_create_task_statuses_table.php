<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateTaskStatusesTable extends Migration
{

    protected $table = 'task_statuses';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create($this->table, function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->unique();
            $table->timestamps();
        });

        DB::table($this->table)->insert([
                ['name' => 'New', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'InProgress', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'OnTesting', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
                ['name' => 'Done', 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()],
            ]);
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
