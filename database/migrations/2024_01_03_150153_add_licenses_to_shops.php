<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLicensesToShops extends Migration
{
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('gst_certificate')->nullable();
            $table->string('import_license')->nullable();
            $table->string('seller_license')->nullable();
            $table->string('ayush_license')->nullable();
            $table->string('factory_license')->nullable();
            $table->string('registration_certificate')->nullable();
            $table->string('iso_certificate')->nullable();
            $table->string('international_license')->nullable();
            $table->string('business_pan')->nullable();
            // Add other columns if needed
        });
    }

    public function down()
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn([
                'gst_certificate',
                'import_license',
                'seller_license',
                'ayush_license',
                'factory_license',
                'registration_certificate',
                'iso_certificate',
                'international_license',
                'business_pan',
            ]);
        });
    }
}
