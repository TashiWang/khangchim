<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('houses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->unsignedInteger('area_id');
            $table->unsignedInteger('user_id');
            $table->string('contact');
            $table->integer('number_of_room');
            $table->integer('number_of_toilet');
            $table->integer('number_of_balcony');
            $table->integer('rent');
            $table->string('featured_image');
            $table->text('images');
            $table->string('status')->default(1);
            $table->foreign('area_id')->references('id')->on('areas');
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('houses');
    }
}
