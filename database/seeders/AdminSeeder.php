<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Stock;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Stock::create([
            'stock' => 100,
            'property_id' => '14b110ad-223f-4d0f-a54a-634430d01efd',
        ]);
    }
}
