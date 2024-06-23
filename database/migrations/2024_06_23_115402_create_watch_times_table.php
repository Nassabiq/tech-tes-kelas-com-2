<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWatchTimesTable extends Migration
{
    public function up()
    {
        Schema::create('watch_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('class_id')->constrained();
            $table->integer('duration'); // duration in minutes
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('watch_times');
    }
}