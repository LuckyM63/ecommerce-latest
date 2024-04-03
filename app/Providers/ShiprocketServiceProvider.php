<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class ShiprocketServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $shiprocketCredentials = json_decode(DB::table('business_settings')->where('type', 'shiprocket')->value('value'), true);

        Config::set('shiprocket', [
            'user_id' => $shiprocketCredentials['user_id'] ?? '',
            'password' => $shiprocketCredentials['password'] ?? '',
        ]);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
