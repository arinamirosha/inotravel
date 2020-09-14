<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestrictionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restrictions', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('value');
        });

        DB::table('restrictions')->insert([
            ['name' => 'animals', 'value' => 'Животные'],
            ['name' => 'houseplants', 'value' => 'Комнатные растения'],
            ['name' => 'no_smoke', 'value' => 'Нельзя курить'],
            ['name' => 'no_drink', 'value' => 'Нельзя пить']
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restrictions');
    }
}
