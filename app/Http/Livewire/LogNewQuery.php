<?php

namespace App\Http\Livewire;

use App\Models\Call;
use App\Models\Client;
use App\Models\Query;
use App\Models\User;
use Livewire\Component;

class LogNewQuery extends Component
{
    public $firstName = '';
    public $lastName = '';
    public $natid = '';
    public $queries;
    public $clients;
    protected $listeners = ['numberChanged' => 'render'];


    public function mount(){
        $this->clients = User::where('utype', '=','Client')->get();
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function changeEvent($value)
    {
        $this->natid = $value;
        $this->emit('numberChanged');
    }

    public function render()
    {
        $this->queries = Query::where('natid', $this->natid)
            ->take(10)
            ->orderByDesc('id')
            ->get();
        $queries = $this->queries;

        $client = Query::where('natid', $this->natid)->first();
        return view('livewire.log-new-query', compact('queries','client'));
    }

}
