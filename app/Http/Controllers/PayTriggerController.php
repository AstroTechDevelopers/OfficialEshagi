<?php

namespace App\Http\Controllers;

use App\Models\DeviceLoan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class PayTriggerController extends Controller
{
    public function selectDeviceToRemoveLock(){
        $loans = DB::table('device_loans as l')
            ->join('users as u', 'u.id', '=', 'l.user_id')
            ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.serial_num', 'l.imei', 'l.device', 'l.device_model')
            ->where('l.loan_status','=', 12)
            ->where('l.locale','=', auth()->user()->locale)
            ->where('l.deleted_at','=', null)
            ->get();
        return view('deviceloans.select-device-removelock', compact('loans'));
    }

    public function removePayTriggerLock($id){
        $loan = DeviceLoan::where('id',$id)->firstOrFail();

        $data = [
            "deviceTag"=>$loan->serial_num,
            "imei"=> $loan->imei,
            "apiKey"=> Config::get('configs.PAYTRIGGER_KEY')
        ];

        $dataToSign="apiKey=".Config::get('configs.PAYTRIGGER_KEY')."&deviceTag=".$loan->serial_num."&imei=".$loan->imei;
        $sig = strtoupper(hash_hmac('sha256', $dataToSign, Config::get('configs.PAYTRIGGER_KEY')));
        $signature = base64_encode($sig);

        $response = Http::withHeaders([
            'sign' => $signature,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'])
            ->withBody(json_encode($data), 'application/json; charset=UTF-8')
            ->post('https://paytrigger.shalltry.com/PayTrigger/api/partner/lock/v1/removeLock');

        $resp=json_decode($response, TRUE);

        if (isset($resp['response']['Errors'])){
            return redirect()->back()->with('error', 'Got this error from PayTrigger: '.$resp['response']['Errors'][0]);
        } else {
            return redirect()->back()->with('success', 'Lock Removed from device.');
        }
    }


}
