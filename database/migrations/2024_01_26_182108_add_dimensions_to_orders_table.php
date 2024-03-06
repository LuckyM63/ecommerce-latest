<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Add columns for weight, height, length, and breadth
            $table->float('weight')->nullable()->after('third_party_delivery_AWB_id');
            $table->float('height')->nullable()->after('weight');
            $table->float('length')->nullable()->after('height');
            $table->float('breadth')->nullable()->after('length');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            // Drop columns if they exist
            $table->dropColumn('weight');
            $table->dropColumn('height');
            $table->dropColumn('length');
            $table->dropColumn('breadth');
        });
    }
}
