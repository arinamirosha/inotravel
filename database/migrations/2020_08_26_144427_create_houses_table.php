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
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('city');
            $table->string('address');
            $table->unsignedSmallInteger('places');
            $table->unsignedBigInteger('facility_id');
            $table->unsignedBigInteger('restriction_id');
            $table->text('info')->nullable();
            $table->string('image')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'facility_id', 'restriction_id']);
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
