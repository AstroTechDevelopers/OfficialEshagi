<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LocaleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('localels')->insert([
            'id' => 1,
            'country' => 'Zambia',
            'country_short' => 'ZM',
            'currency_code' => 'ZMW',
            'currency_name' => 'Zambian Kwacha',
            'symbol' => 'K',
            'country_code' => '260',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
