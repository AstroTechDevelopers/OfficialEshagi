<?php

namespace App\Http\Livewire;

use App\Models\Loan;
use Illuminate\Support\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use PowerComponents\LivewirePowerGrid\Button;
use PowerComponents\LivewirePowerGrid\Column;
use PowerComponents\LivewirePowerGrid\PowerGrid;
use PowerComponents\LivewirePowerGrid\PowerGridEloquent;
use PowerComponents\LivewirePowerGrid\PowerGridComponent;
use PowerComponents\LivewirePowerGrid\Traits\ActionButton;

class LoanTable extends PowerGridComponent
{
    use ActionButton;

    /*
    |--------------------------------------------------------------------------
    |  Features Setup
    |--------------------------------------------------------------------------
    | Setup Table's general features
    |
    */
    public function setUp()
    {
        $this->showCheckBox()
            ->showPerPage()
            ->showExportOption('All Loans', ['excel', 'csv'])
            ->showSearchInput();
    }

    /*
    |--------------------------------------------------------------------------
    |  Datasource
    |--------------------------------------------------------------------------
    | Provides data to your Table using a Model or Collection
    |
    */
    public function datasource(): \Illuminate\Support\Collection
    {
        if ((auth()->user()->hasRole('root') || auth()->user()->hasRole('admin') || auth()->user()->hasRole('group') )) {
            return DB::table('loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.deleted_at','=', null)
                ->get();
        } else {
            return DB::table('loans as l')
                ->join('users as u', 'u.id', '=', 'l.user_id')
                ->select('l.id', 'u.first_name', 'u.last_name', 'u.natid', 'l.amount', 'l.monthly', 'l.loan_status', 'l.loan_type')
                ->where('l.locale','=', auth()->user()->locale)
                ->where('l.deleted_at','=', null)
                ->get();
        }
         //Loan::query();
    }

    /*
    |--------------------------------------------------------------------------
    |  Relationship Search
    |--------------------------------------------------------------------------
    | Configure here relationships to be used by the Search and Table Filters.
    |
    */
    public function relationSearch(): array
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    |  Add Column
    |--------------------------------------------------------------------------
    | Make Datasource fields available to be used as columns.
    | You can pass a closure to transform/modify the data.
    |
    */
    public function addColumns(): ?PowerGridEloquent
    {
        return PowerGrid::eloquent()
            ->addColumn('id')
            ->addColumn('natid')
            ->addColumn('loan_type')
            ->addColumn('loan_status')
            ->addColumn('amount')
            ->addColumn('monthly');
    }

    /*
    |--------------------------------------------------------------------------
    |  Include Columns
    |--------------------------------------------------------------------------
    | Include the columns added columns, making them visible on the Table.
    | Each column can be configured with properties, filters, actions...
    |
    */
    public function columns(): array
    {
        return [
            Column::add()
                ->title(__('ID'))
                ->field('id')
                ->makeInputRange(),

            Column::add()
                ->title(__('National ID'))
                ->field('natid')
                ->makeInputRange(),

            Column::add()
                ->title(__('LOAN TYPE'))
                ->field('loan_type')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::add()
                ->title(__('LOAN STATUS'))
                ->field('loan_status')
                ->sortable()
                ->searchable()
                ->makeInputText(),

            Column::add()
                ->title(__('AMOUNT'))
                ->field('amount')
                ->sortable()
                ->searchable(),

            Column::add()
                ->title(__('MONTHLY'))
                ->field('monthly')
                ->sortable()
                ->searchable(),
        ]
;
    }

    /*
    |--------------------------------------------------------------------------
    | Actions Method
    |--------------------------------------------------------------------------
    | Enable this section only when you have defined routes for these actions.
    |
    */

    /*
    public function actions(): array
    {
       return [
           Button::add('edit')
               ->caption(__('Edit'))
               ->class('bg-indigo-500 text-white')
               ->route('loan.edit', ['loan' => 'id']),

           Button::add('destroy')
               ->caption(__('Delete'))
               ->class('bg-red-500 text-white')
               ->route('loan.destroy', ['loan' => 'id'])
               ->method('delete')
        ];
    }
    */

    /*
    |--------------------------------------------------------------------------
    | Edit Method
    |--------------------------------------------------------------------------
    | Enable this section to use editOnClick() or toggleable() methods
    |
    */

    /*
    public function update(array $data ): bool
    {
       try {
           $updated = Loan::query()->find($data['id'])->update([
                $data['field'] => $data['value']
           ]);
       } catch (QueryException $exception) {
           $updated = false;
       }
       return $updated;
    }

    public function updateMessages(string $status, string $field = '_default_message'): string
    {
        $updateMessages = [
            'success'   => [
                '_default_message' => __('Data has been updated successfully!'),
                //'custom_field' => __('Custom Field updated successfully!'),
            ],
            'error' => [
                '_default_message' => __('Error updating the data.'),
                //'custom_field' => __('Error updating custom field.'),
            ]
        ];

        return ($updateMessages[$status][$field] ?? $updateMessages[$status]['_default_message']);
    }
    */

    public function template(): ?string
    {
        return null;
    }

}
