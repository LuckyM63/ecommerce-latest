<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultPickupAddressToShops extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->unsignedBigInteger('default_pickup_address_id')->nullable();
            $table->foreign('default_pickup_address_id')->references('id')->on('shop_pickup_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropForeign(['default_pickup_address_id']);
            $table->dropColumn('default_pickup_address_id');
        });
    }
}
