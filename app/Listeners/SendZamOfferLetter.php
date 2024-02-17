<?php

namespace App\Listeners;

use App\Events\ZamManagerApprovedLoan;
use App\Mail\SendZambiaOfferLetter;
use App\Models\LoanApproval;
use App\Models\User;
use App\Models\Zambian;
use App\Notifications\MailZambiaOfferLetter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;

class SendZamOfferLetter
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ZamManagerApprovedLoan  $event
     * @return void
     */
    public function handle(ZamManagerApprovedLoan $event)
    {
        $client = Zambian::where('id',$event->loan->zambian_id)->firstOrFail();
        $loan = $event->loan;

        $signatureUrl = public_path('zam_signs/'.$client->sign_pic) ;
        $arrContextOptions=array(
            "ssl"=>array(
                "verify_peer"=>false,
                "verify_peer_name"=>false,
            ),
        );
        $type = pathinfo($signatureUrl, PATHINFO_EXTENSION);
        $signatureData = file_get_contents($signatureUrl, false, stream_context_create($arrContextOptions));
        $signatureBase64Data = base64_encode($signatureData);
        $encodedSignature = 'data:image/' . $type . ';base64,' . $signatureBase64Data;

        $pdf = \PDF::loadView('zamloans.zam-offer-letter', compact('client', 'loan','encodedSignature'));

        try {
            $randOtp = str_random(8);

            $user = User::where('natid', $client->nrc)->first();

            $loanApproval = LoanApproval::create([
                'loan_id' => $loan->id,
                'user_id' => $user->id,
                'locale' => $user->locale,
                'otp' => Hash::make($randOtp),
            ]);

            Mail::to($client->email)
                ->cc('accounts@eshagi.com')
                ->send(new SendZambiaOfferLetter($client, $loan, $pdf, $randOtp,$loanApproval));

            $sendSMS = Http::post("https://bulksms.zamtel.co.zm/api/v2.1/action/send/api_key/".Config::get('configs.ZAMTEL_API_KEY')."/contacts/260".$client->mobile."/senderId/eShagi/message/".urlencode("Good day, Your OTP for loan approval is: ".$randOtp))
                ->body();

           $json = json_decode($sendSMS, true);

//            print_r($json['success']); boolean
//            print_r($json['responseText']); "responseText" => "SMS(es) have been queued for delivery"

        } catch (\Exception $exception){
            Log::error('Failed to send email notification: '.$exception);
            Log::error('SMS notification: '.$exception);
        }
    }
}
