<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->string('class_name');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->boolean('mon');
            $table->boolean('tues');
            $table->boolean('wed');
            $table->boolean('thur');
            $table->boolean('fri');
            $table->string('icon_name');
            $table->string('bg_color');
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
        Schema::dropIfExists('courses');
    }
}
