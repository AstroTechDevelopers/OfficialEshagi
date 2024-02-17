<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MasettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('masettings')->insert([
            'id' => 1,
            'interest' => 15.00,
            'self_interest' => 15.00,
            'creditRate' => 3.10,
            'usd_creditRate' => 3.10,
            'weekly_target' => '33',
            'om_interest' => 16.00,
            'loan_interest_method' => 'reducing_rate_equal_installments',
            'leads_allocation' => '20',
            'fcb_username' => 'fdzemunyasi@cbz.co.zw',
            'fcb_password' => '2020Apr@',
            'reds_username' => 'reds_username',
            'reds_password' => 'reds_password',
            'ndas_username' => 'ndas_username',
            'ndas_password' => 'ndas_password',
            'crb_infinity_code' => 'zm123456789',
            'crb_username' => 'ZMDU_AMZ',
            'crb_password' => 'LUo!Z96m1}w%RtU',
            'signing_ceo' => 'Michael Gova',
            'ceo_encoded_signature' => 'signature',
            'cbz_authorizer' => 'mchinhoro ad1mn vguyo mguyo',
            'device_penalty' => 500.00,
            'loan_penalty' => 500.00,
            'bulksmsweb_baseurl' => 'http://portal.bulksmsweb.com/index.php?app=ws&u=astrocredit&h=6b46a270fc77b3bc63c9ed6863e4635e&op=pv&',
            'last_changed_by' => 'kaynerd',
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
