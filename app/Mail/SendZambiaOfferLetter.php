<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendZambiaOfferLetter extends Mailable
{
    use Queueable, SerializesModels;
    public $client;
    public $loan;
    public $pdf;
    public $randOtp;
    public $loanApproval;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($client, $loan, $pdf, $randOtp, $loanApproval)
    {
        $this->loan = $loan;
        $this->client = $client;
        $this->pdf = $pdf;
        $this->randOtp = $randOtp;
        $this->loanApproval = $loanApproval;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.email-zam-offer-letter')
            ->subject('eShagi Loan Offer Letter')
            ->attachData($this->pdf->output(), "OfferLetter_".$this->client->nrc.".pdf", [
                'mime' => 'application/pdf',
            ])
            ->attach(public_path() .'/DirectDeductionForm.pdf', [
                'as' => 'DirectDeductionForm.pdf',
                'mime' => 'application/pdf',
            ])
            ->with([
            'client' => $this->client,
            'loan' => $this->loan,
            'otp' => $this->randOtp,
            'url' => 'https://eshagi.com/lookup-loan-approvals/'.$this->loanApproval->id,
        ]);
    }
}
