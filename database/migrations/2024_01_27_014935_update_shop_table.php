<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('shops', function (Blueprint $table) {
            $table->string('pickup_address')->nullable()->after('address');
            $table->boolean('is_added_on_shiprocket')->default(false)->after('pickup_address');
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
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn('pickup_address');
            $table->dropColumn('is_added_on_shiprocket');
        });
    }
}
