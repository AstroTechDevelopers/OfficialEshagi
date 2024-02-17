<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadNationalIds extends Component
{
    use WithNotyf,WithFileUploads;
    public $national_id1,$national_id2;

    private function resetInputFields(){
        $this->national_id1 = null;
        $this->national_id2 = null;
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
            'national_id1' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:4096',
            'national_id2' => 'image|mimes:jpg,jpeg,png,svg,gif|max:4096',
        ]);

        $dataValid['national_id1'] = $this->national_id1->storeAs('merch_natids', auth()->user()->natid.'.'.$this->national_id1->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->national_id1 = $dataValid['national_id1'];
        $merchantKyc->national_id1_stat = true;

        if ($dataValid['national_id2'] != null){
            $dataValid['national_id2'] = $this->national_id2->storeAs('merch_natids', auth()->user()->natid.'_2.'.$this->national_id2->extension() , 'public');

            $merchantKyc->national_id2 = $dataValid['national_id2'];
            $merchantKyc->national_id2_stat = true;
        }

        $merchantKyc->save();

        $this->notyfSuccess('National ID Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-national-ids', compact('kyc'));
    }
}
