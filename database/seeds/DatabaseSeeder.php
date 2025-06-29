<?php

use Illuminate\Database\Seeder;
use App\Models\offer;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
         $this->call([
             AdminRoleTable::class,
             AdminTable::class,
             SellerTableSeeder::class,
             offerSeeder::class
         ]);
    }

    
}
