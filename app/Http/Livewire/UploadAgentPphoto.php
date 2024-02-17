<?php

namespace App\Http\Livewire;

use App\Models\MerchantKyc;
use Kapouet\Notyf\Traits\Livewire\WithNotyf;
use Livewire\Component;
use Livewire\WithFileUploads;

class UploadAgentPphoto extends Component
{
    use WithNotyf,WithFileUploads;
    public $pphoto1;

    private function resetInputFields(){
        $this->pphoto1 = null;
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
            'pphoto1' => 'required|image|mimes:jpg,jpeg,png,svg,gif|max:4096',
        ]);

        $dataValid['pphoto1'] = $this->pphoto1->storeAs('merch_pphotos', auth()->user()->natid.'.'.$this->pphoto1->extension() , 'public');

        $merchantKyc = MerchantKyc::where('natid',auth()->user()->natid)->first();
        $merchantKyc->pphoto1 = $dataValid['pphoto1'];
        $merchantKyc->pphoto1_stat = true;
        $merchantKyc->save();

        $this->notyfSuccess('Passport photos Uploaded');
        $this->emit('agentKycUploaded');
        $this->resetInputFields();
    }

    public function render()
    {
        $kyc = MerchantKyc::where('natid', auth()->user()->natid)->first();

        return view('livewire.upload-agent-pphoto', compact('kyc'));
    }
}
