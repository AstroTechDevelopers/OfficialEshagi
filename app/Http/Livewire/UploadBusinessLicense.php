<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadBusinessLicense extends Component
{
    use WithNotyf,WithFileUploads;
    public $bus_licence;

    private function resetInputFields(){
        $this->bus_licence = null;
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
            'bus_licence' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf|max:4096',
        ]);

        $dataValid['bus_licence'] = $this->bus_licence->storeAs('bus_licence_pics', auth()->user()->natid.'.'.$this->bus_licence->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->bus_licence = $dataValid['bus_licence'];
        $merchantKyc->bus_licence_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('Business Licence Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-business-license', compact('kyc'));
    }
}
