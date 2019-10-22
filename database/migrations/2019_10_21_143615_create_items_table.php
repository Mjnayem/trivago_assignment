<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name',250);
            $table->integer('rating')->default(0);

            $table->string('category',128);
            $table->bigInteger('location_id')->unsigned();
            $table->unsignedBigInteger('hotelier_id');

            $table->text('image')->nullable();
            $table->unsignedBigInteger('reputation')->default(0);
            $table->enum('reputationBadge',array('red','green','yellow'))->default('red');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('availability')->default(0);
            $table->timestamps();

            $table->foreign('location_id')->references('id')->on('locations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('items');
    }
}
