<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AgentKycUploadChecker extends Component
{
    protected $listeners = ['agentKycUploaded' => 'render'];
    public $yuser,$kyc;

    public function render()
    {
        return view('livewire.agent-kyc-upload-checker');
    }
}
