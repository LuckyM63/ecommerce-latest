<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDimensionsToProductsTable extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('product_height')->nullable();
            $table->string('product_length')->nullable();
            $table->string('product_breadth')->nullable();
            $table->string('product_weight')->nullable();
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('product_height');
            $table->dropColumn('product_length');
            $table->dropColumn('product_breadth');
            $table->dropColumn('product_weight');
        });
    }
}
