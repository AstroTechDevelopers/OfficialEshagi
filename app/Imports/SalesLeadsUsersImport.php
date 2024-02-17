<?php

namespace App\Imports;

use App\Models\Profile;
use App\Models\User;
use App\Traits\CaptureIpTrait;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Support\Collection;

class SalesLeadsUsersImport implements ToCollection, WithBatchInserts , WithChunkReading
{
    /**
    */
    public function collection(Collection $rows)
    {
        $ipAddress = new CaptureIpTrait();
        $profile = new Profile();

        foreach ($rows[0] as $lead) {
            if (!empty($lead[8])){
                $user = User::create([
                    'name'             => substr($lead[1],0, 1).$lead[2],
                    'first_name'       => $lead[1],
                    'last_name'        => $lead[2],
                    'email'            => substr($lead[1],0, 1).$lead[2].'@gmail.com',
                    'natid'            => $lead[4],
                    'mobile'            => $lead[5],
                    'utype'            => 'Client',
                    'password'         => Hash::make('pass12345'),
                    'token'            => str_random(64),
                    'admin_ip_address' => $ipAddress->getClientIp(),
                    'activated'        => 1,
                    'locale'        => $lead[10],
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
