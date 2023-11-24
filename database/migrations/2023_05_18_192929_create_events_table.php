<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->string('year');
            $table->string('month')->nullable();
            $table->string('day')->nullable();
            $table->string('date')->nullable();
            $table->unsignedBigInteger('catagory_id')->nullable();
            $table->string('event')->nullable();
            $table->longText('detail')->nullable();
            $table->string('image')->nullable();
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('events');
    }
};
