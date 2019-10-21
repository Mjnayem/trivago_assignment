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

            $table->bigInteger('category')->unsigned();
            $table->bigInteger('location_id')->unsigned();

            $table->text('image_url')->nullable();
            $table->unsignedBigInteger('reputation')->default(0);
            $table->enum('reputation_badge',array('red','green','yellow'))->default('red');
            $table->unsignedBigInteger('price')->default(0);
            $table->unsignedBigInteger('availability')->default(0);
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
        Schema::dropIfExists('items');
    }
}
