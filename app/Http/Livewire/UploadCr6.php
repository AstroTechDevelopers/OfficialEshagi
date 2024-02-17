<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCr6 extends Component
{
    use WithNotyf,WithFileUploads;
    public $cr6;

    private function resetInputFields(){
        $this->cr6 = null;
        $this->render();
    }
    /**
     * Write code on Method
     *
     *
     */
    public function save()
    {
        $dataValid = $this->validate([
            'cr6' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf|max:4096',
        ]);

        $dataValid['cr6'] = $this->cr6->storeAs('cr6_pics', auth()->user()->natid.'.'.$this->cr6->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->cr6 = $dataValid['cr6'];
        $merchantKyc->cr6_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('CR6 Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }
    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-cr6', compact('kyc'));
    }
}
