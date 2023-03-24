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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('modelCar_id');
            $table->string('plate', 10)->unique();
            $table->boolean('available');
            $table->integer('km');
            $table->timestamps();

            //foreign key (constraints)
            $table->foreign('modelCar_id')->references('id')->on('model_cars');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cars');
    }
};
