<?php

namespace App\Http\Livewire;

use App\Models\Client;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ReassignLoanTable extends Component
{

    public $cid,$creator,$first_name,$last_name,$amount,$natid;

    public function mount(){
//        $loans = DB::table('loans as l')
//            ->join('clients as c', 'c.id','=','l.client_id')
//            ->select('l.id','c.id as cid','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.loan_status','l.loan_type')
//            ->where('l.locale','=',auth()->user()->locale)
//            ->where('l.loan_status','<',12)
//            ->where('l.deleted_at','=', null)
//            ->get();
//        $this->loans = $loans;
    }

    public function OpenEditCountryModal($id){
        $info = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.loan_status','l.loan_type')
            ->where('c.id','=',$id)
            ->where('l.deleted_at','=', null)
            ->first();

        $this->creator = $info->creator;
        $this->first_name = $info->first_name;
        $this->last_name = $info->last_name;
        $this->amount = $info->amount;
        $this->natid = $info->natid;
        $this->cid = $info->cid;

        $this->dispatchBrowserEvent('OpenEditCountryModal',[
            'id'=>$id
        ]);
    }

    public function update(){
        $cid = $this->cid;
        $this->validate([
            'creator'=>'required',
        ],[
            'creator.required'=>'Who is the loan agent?',
        ]);

        $update = Client::find($cid)->update([
            'creator'=>$this->creator,
        ]);

        if($update){
            $this->dispatchBrowserEvent('CloseEditCountryModal');
        }
    }

    public function render()
    {
        $loans = DB::table('loans as l')
            ->join('clients as c', 'c.id','=','l.client_id')
            ->select('l.id','c.id as cid','c.creator','c.first_name','c.last_name','c.natid','l.amount','l.loan_status','l.loan_type')
            ->where('l.locale','=',auth()->user()->locale)
            ->where('l.loan_status','<',12)
            ->where('l.deleted_at','=', null)
            ->get();

        return view('livewire.reassign-loan-table', compact('loans'));
    }
}
