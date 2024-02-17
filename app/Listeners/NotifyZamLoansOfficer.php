<?php

namespace App\Listeners;

use App\Events\NewLOZambiaLoanApproval;
use App\Models\User;
use App\Models\Zambian;
use App\Notifications\NewZamLoansOfficerApproval;
use App\Notifications\NewZimLoansOfficerApproval;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use jeremykenedy\LaravelRoles\Models\Role;

class NotifyZamLoansOfficer
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
     * @param  \App\Events\NewLOZambiaLoanApproval  $event
     * @return void
     */
    public function handle(NewLOZambiaLoanApproval $event)
    {
        $client = Zambian::where('id',$event->loan->zambian_id)->firstOrFail();

        $loRole = Role::where('slug','=','loansofficer')->firstOrFail();
        $lofficers = User::join('role_user as r', 'r.user_id', '=', 'users.id')
            ->where('users.utype','=','System')
            ->where('r.role_id','=',$loRole->id)
            ->where('users.locale','=','2')
            ->get();

        foreach ($lofficers as $officer) {
            try {
                Notification::route('mail', $officer->email)->notify(new NewZamLoansOfficerApproval($event->loan, $client));
            } catch (\Exception $exception){
                Log::error('Failed to send email notification: '.$exception);
            }
        }
    }
}
