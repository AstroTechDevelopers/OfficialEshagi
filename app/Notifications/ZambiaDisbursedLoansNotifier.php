<?php

namespace App\Notifications;

use App\Exports\ZambiaDisbursedLoansExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ZambiaDisbursedLoansNotifier extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Zambia Disbursed Loans Report')
            ->markdown('emails.zam-disbursedloans-rep')
            ->attach(
                Excel::download(
                    new ZambiaDisbursedLoansExport(),
                    'Zambia Daily Disbursed Loans Report - '.date('d-m-Y').'.xlsx'
                )->getFile(), ['as' => 'Zambia Daily Disbursed Loans Report - '.date('d-m-Y').'.xlsx']
            );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
