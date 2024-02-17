<?php

namespace App\Listeners;

use App\Events\LONewLoanApproval;
use App\Models\Client;
use App\Models\User;
use App\Notifications\NewZimLoansOfficerApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use jeremykenedy\LaravelRoles\Models\Role;

class EmailLoansOfficer
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
     * @param  \App\Events\LONewLoanApproval  $event
     * @return void
     */
    public function handle(LONewLoanApproval $event)
    {
        $client = Client::where('id',$event->loan->client_id)->firstOrFail();
        $newlimit = $client->cred_limit-$event->loan->amount;
        DB::table('clients')
            ->where('id',$event->loan->client_id)
            ->update(['cred_limit' => $newlimit,'updated_at' => now()]);

        $loRole = Role::where('slug','=','loansofficer')->firstOrFail();
        $lofficers = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$loRole->id)
            ->where('users.locale','=',$event->loan->locale)
            ->get();

        foreach ($lofficers as $officer) {
            try {
                Notification::route('mail', $officer->email)->notify(new NewZimLoansOfficerApproval($event->loan, $client));
            } catch (\Exception $exception){
                Log::error('Failed to send email notification: '.$exception);
            }
        }
    }
}
