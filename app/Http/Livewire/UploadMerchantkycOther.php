<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadMerchantkycOther extends Component
{
    use WithNotyf,WithFileUploads;
    public $other;

    private function resetInputFields(){
        $this->other = null;
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
            'other' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf|max:4096',
        ]);

        $dataValid['other'] = $this->other->storeAs('other_pics', auth()->user()->natid.'.'.$this->other->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->other = $dataValid['other'];
        $merchantKyc->other_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('Other KYC Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();
        return view('livewire.upload-merchantkyc-other', compact('kyc'));
    }
}
