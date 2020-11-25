<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIndexesToTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->index('name');
        });

        Schema::table('houses', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('city');
            $table->index('places');
            $table->index('name');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->index('house_id');
            $table->index('user_id');
            $table->index('arrival');
            $table->index('departure');
            $table->index('status');
        });

        Schema::table('booking_histories', function (Blueprint $table) {
            $table->dropIndex('booking_histories_user_id_created_at_index'); // old
            $table->index('user_id');
            $table->index('booking_id');
            $table->index('type');
        });
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
