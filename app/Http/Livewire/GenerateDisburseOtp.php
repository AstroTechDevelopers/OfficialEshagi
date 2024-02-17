<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class GenerateDisburseOtp extends Component
{

    public $loanId;

    public function generateTheOtp($loanId){

    }

    public function render()
    {
        return view('livewire.generate-disburse-otp');
    }
}
