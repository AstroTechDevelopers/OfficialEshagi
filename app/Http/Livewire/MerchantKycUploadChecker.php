<?php

namespace App\Http\Livewire;

use Livewire\Component;

class MerchantKycUploadChecker extends Component
{
    protected $listeners = ['merchantKycUploaded' => 'render'];
    public $yuser,$kyc;

    public function render()
    {
        return view('livewire.merchant-kyc-upload-checker');
    }
}
