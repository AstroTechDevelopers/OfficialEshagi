<?php

namespace App\Imports;

use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class UsersSalesRepImport implements ToCollection, WithBatchInserts , WithChunkReading
{
    /**
     */

    public function collection(Collection $rows)
    {
        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();

        foreach ($rows as $srep) {

            if (!empty($srep[5])){
                $user = User::create([
                    'name'             => 'rep_'.strtolower(substr($srep[1],0, 1).$srep[2]),
                    'first_name'       => $srep[1],
                    'last_name'        => $srep[2],
                    'email'            => $srep[3] ?? substr($srep[1],0, 1).$srep[2].'@gmail.com',
                    'natid'            => $srep[4] ?? $srep[5],
                    'mobile'           => $srep[5],
                    'utype'            => 'Representative',
                    'password'         => Hash::make('pa55word'),
                    'token'            => str_random(64),
                    'admin_ip_address' => $ipAddress->getClientIp(),
                    'activated'        => 1,
                    'locale'           => 1,
                ]);

                $user->profile()->save($profile);
                $user->attachRole(10);
                $user->save();
            } else {
                break;
            }
        }
    }

    public function batchSize(): int
    {
        return 5000;
    }

    public function chunkSize(): int
    {
        return 5000;
    }
}

