<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadCertificateIncorporation extends Component
{
    use WithNotyf,WithFileUploads;
    public $certificate;

    private function resetInputFields(){
        $this->certificate = null;
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
            'certificate' => 'required|mimes:jpg,jpeg,png,svg,gif,pdf|max:4096',
        ]);

        $dataValid['certificate'] = $this->certificate->storeAs('merchant_certs', auth()->user()->natid.'.'.$this->certificate->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->cert_incorp = $dataValid['certificate'];
        $merchantKyc->cert_incorp_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('Certificate Uploaded');
        $this->emit('merchantKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-certificate-incorporation',compact('kyc'));
    }
}
