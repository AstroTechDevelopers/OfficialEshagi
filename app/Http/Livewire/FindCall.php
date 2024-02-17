<?php

namespace App\Http\Livewire;

use App\Models\Call;
use Livewire\Component;

class FindCall extends Component
{
    public $mobile = '';
    public $calls;
    protected $listeners = ['numberChanged' => 'render'];

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function changeEvent($value)
    {
        $this->mobile = $value;
        $this->emit('numberChanged');
    }

    public function render()
    {
        $this->calls = Call::where('mobile', $this->mobile)
            ->take(10)
            ->orderByDesc('id')
            ->get();
        $calls = $this->calls;
        $client = Call::where('mobile', $this->mobile)->first();
        return view('livewire.find-call', compact('calls','client'));
    }
}
