<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCr14 extends Component
{
    use WithNotyf,WithFileUploads;
    public $cr14;

    private function resetInputFields(){
        $this->cr14 = null;
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
            'cr14' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf|max:4096',
        ]);

        $dataValid['cr14'] = $this->cr14->storeAs('cr14_pics', auth()->user()->natid.'.'.$this->cr14->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->cr14 = $dataValid['cr14'];
        $merchantKyc->cr14_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('CR14 Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-cr14', compact('kyc'));
    }
}
