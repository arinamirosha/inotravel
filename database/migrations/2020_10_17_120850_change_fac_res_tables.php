<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ChangeFacResTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('facilities')->where('name', 'internet')->update(['value' => 'Internet']);
        DB::table('facilities')->where('name', 'wifi')->update(['value' => 'Wi-Fi']);
        DB::table('facilities')->where('name', 'cable_tv')->update(['value' => 'Cable TV']);
        DB::table('facilities')->where('name', 'conditioner')->update(['value' => 'Conditioner']);
        DB::table('facilities')->where('name', 'washer')->update(['value' => 'Washer']);

        DB::table('restrictions')->where('name', 'animals')->update(['value' => 'Animals']);
        DB::table('restrictions')->where('name', 'houseplants')->update(['value' => 'Houseplants']);
        DB::table('restrictions')->where('name', 'no_smoke')->update(['value' => 'No smoke']);
        DB::table('restrictions')->where('name', 'no_drink')->update(['value' => 'No drink']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
