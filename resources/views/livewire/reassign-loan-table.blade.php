<div>
    <h4 class="header-title">Reassign Loans</h4>
    <p>Loans that can be reassigned for to other agents</p>
    <table id="datatable-buttons" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
        <thead>
        <tr>
            <th>Agent</th>
            <th>Full name</th>
            <th>National ID</th>
            <th>Total</th>
            <th>Loan Type</th>
            <th>Loan Status</th>
            <th>Actions</th>
        </tr>
        </thead>

        <tbody>
        @foreach($loans as $loan)
            <tr>
                <td>{{$loan->creator}}</td>
                <td>{{$loan->first_name.' '.$loan->last_name}}</td>
                <td>{{$loan->natid}}</td>
                <td>{{$loan->amount}}</td>
                <td>{{getLoantype($loan->loan_type)}}</td>
                <td>{{getLoanstatus($loan->loan_status)}}</td>
                <td style="white-space: nowrap;">

                    <button class="btn btn-sm btn-success" wire:click="OpenEditCountryModal({{$loan->cid}})" >
                        <i class="mdi mdi-swap-vertical" aria-hidden="true"></i>
                    </button>
                </td>

            </tr>
        @endforeach
        </tbody>
    </table>
    @include('modals.modal-change-agent')
</div>
