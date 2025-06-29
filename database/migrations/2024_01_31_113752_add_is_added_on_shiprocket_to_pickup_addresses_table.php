<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsAddedOnShiprocketToPickupAddressesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shop_pickup_addresses', function (Blueprint $table) {
            $table->boolean('is_added_on_shiprocket')->default(false);
        });
    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shop_pickup_addresses', function (Blueprint $table) {
            $table->dropColumn('is_added_on_shiprocket');
        });
    }
}
