<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityStatePincodeInShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shops', function (Blueprint $table) {
            // Add columns after 'address'
            $table->string('city', 200)->collation('utf8mb4_unicode_ci')->nullable()->after('address');
            $table->string('state', 100)->collation('utf8mb4_unicode_ci')->nullable()->after('city');
            $table->string('country', 100)->collation('utf8mb4_unicode_ci')->nullable()->after('state');
            $table->string('pinCode', 25)->collation('utf8mb4_unicode_ci')->nullable()->after('country');

            // Add columns after 'pickup_address'
            $table->string('pickup_city', 200)->collation('utf8mb4_unicode_ci')->nullable()->after('pickup_address');
            $table->string('pickup_state', 100)->collation('utf8mb4_unicode_ci')->nullable()->after('pickup_city');
            $table->string('pickup_country', 100)->collation('utf8mb4_unicode_ci')->nullable()->after('pickup_state');
            $table->string('pickup_pinCode', 25)->collation('utf8mb4_unicode_ci')->nullable()->after('pickup_country');
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
            // Drop the added columns if the migration is rolled back
            $table->dropColumn('city');
            $table->dropColumn('state');
            $table->dropColumn('country');
            $table->dropColumn('pinCode');

            $table->dropColumn('pickup_city');
            $table->dropColumn('pickup_state');
            $table->dropColumn('pickup_country');
            $table->dropColumn('pickup_pinCode');
        });
    }
}
