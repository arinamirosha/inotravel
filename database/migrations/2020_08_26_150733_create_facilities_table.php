<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('value');
        });

        DB::table('facilities')->insert([
            ['name' => 'internet', 'value' => 'Интернет'],
            ['name' => 'wifi', 'value' => 'Wi-Fi'],
            ['name' => 'cable_tv', 'value' => 'Кабельное ТВ'],
            ['name' => 'conditioner', 'value' => 'Кондиционер'],
            ['name' => 'washer', 'value' => 'Стиральная машина']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facilities');
    }
}
