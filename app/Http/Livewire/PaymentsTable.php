<?php

namespace App\Http\Livewire;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PaymentsTable extends DataTableComponent
{
    protected $model = Payment::class;

    public function columns(): array
    {
        return [
            Column::make('Loan ID', 'ld_loan_id')
                ->sortable()
                ->searchable(),
            Column::make('Amount', 'amount')
                ->sortable()
                ->searchable(),
            Column::make('Collection Date', 'collection_date')
                ->sortable()
                ->searchable(),
            Column::make('Description', 'description')
                ->sortable()
                ->searchable(),
        ];
    }

    public function builder(): Builder
    {
        return Payment::query();
    }

//    public function render()
//    {
//        return view('livewire.payments-table');
//    }

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }
}
