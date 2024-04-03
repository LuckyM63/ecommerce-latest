<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\offer;

class offerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        offer::create([
            'offer_id'=>'offer_NjKxNWvPoNGXzk',
            'status'=>'on'

        ]);
    }
}
