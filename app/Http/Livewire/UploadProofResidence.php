<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use App\Models\Partner;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadProofResidence extends Component
{
    use WithNotyf,WithFileUploads;
    public $proof_of_res;

    private function resetInputFields(){
        $this->proof_of_res = null;
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
            'proof_of_res' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:4096',
        ]);

        $dataValid['proof_of_res'] = $this->proof_of_res->storeAs('merchspor_pics', auth()->user()->natid.'.'.$this->proof_of_res->extension() , 'public');
        $partner = Partner::where('regNumber',auth()->user()->natid)->first();
        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->proof_of_res = $dataValid['proof_of_res'];
        $merchantKyc->proof_of_res_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('Proof of residence Uploaded');
        if ($partner->partner_type == 'Merchant'){
            $this->emit('merchantKycUploaded');
        } else {
            $this->emit('agentKycUploaded');
        }
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-proof-residence', compact('kyc'));
    }
}
