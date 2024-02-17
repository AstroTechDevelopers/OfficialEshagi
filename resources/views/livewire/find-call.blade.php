<div class="row">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">Call Details</h4>
                <p class="card-title-desc">Outgoing Call details. <span class="text-danger">Please make sure you verify the client details, during or after the call.</span></p>

                <!-- Nav tabs -->
                <ul class="nav nav-pills" role="tablist">
                    <li class="nav-item waves-effect waves-light">
                        <a class="nav-link active" data-toggle="tab" href="#call" role="tab">
                            <i class="fas fa-phone-volume mr-1"></i> <span class="d-none d-md-inline-block">Call Details</span>
                        </a>
                    </li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content p-3">
                    <div class="tab-pane active" id="call" role="tabpanel">
                        {!! Form::open(array('route' => 'out.goingcall', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                        {!! csrf_field() !!}
                        <input type="hidden" name="agent" value="{{auth()->user()->name}}">

                        <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                            {!! Form::label('mobile', 'Outgoing Call Number', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input wire:click="changeEvent($event.target.value)" wire:model="mobile" class="form-control" type="text" placeholder="e.g. 0775667788" value="{{ old('mobile') }}" id="mobile" name="mobile" required>
                                </div>
                                @if ($errors->has('mobile'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('client') ? ' has-error ' : '' }}">
                            {!! Form::label('client', 'Company or Individual Name', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    <input class="form-control" type="text" placeholder="e.g. 56-123456-U-17 or Nil" value="{{ $client->client ?? old('client') }}" id="client" name="client" required>
                                </div>
                                @if ($errors->has('client'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('client') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group has-feedback row {{ $errors->has('notes') ? ' has-error ' : '' }}">
                            {!! Form::label('notes', 'Notes', array('class' => 'col-md-3 control-label')); !!}
                            <div class="col-md-9">
                                <div class="input-group">
                                    {!! Form::textarea('notes', NULL, array('id' => 'notes', 'class' => 'form-control', 'placeholder' => 'e.g. Any details or notes to be placed here.' , 'required')) !!}
                                </div>
                                @if ($errors->has('notes'))
                                    <span class="help-block">
                                            <strong>{{ $errors->first('notes') }}</strong>
                                        </span>
                                @endif
                            </div>
                        </div>
                        <br>

                        <input class="form-check-input" type="checkbox" name="setAppointment" id="setAppointment" >
                        <label class="form-check-label" for="setAppointment">
                            Does the client want a follow up?
                        </label>
                        <input type="text" name="appointment" id="appointment" class="form-control datepicker-here{{ $errors->has('appointment') ? ' is-invalid' : '' }}" placeholder="Select a appointment" data-timepicker="true" data-date-format='yyyy-mm-dd' data-time-format='hh:ii' data-language="en" autocomplete="off">

                        <br>
                        <input class="form-check-input" type="checkbox" name="isSale" id="isSale">
                        <label class="form-check-label" for="isSale">
                            Did Client Accept Loan?
                        </label>
                        <br><br>
                        {!! Form::button('Record Call', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                        {!! Form::close() !!}
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-body">

                <h4 class="header-title">Call History</h4>
                <p class="card-title-desc">Summary of calls made regarding this lead</p>

                    @if($calls)
                        <div id="accordion">
                            @foreach($calls as $call)
                                <div class="card mb-0">
                                    <div class="card-header" id="heading{{$loop->index}}">
                                        <h5 class="m-0 font-size-14">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#collapse{{$call->id}}" aria-expanded="true"
                                               aria-controls="collapse{{$loop->index}}" class="text-dark">
                                                Call #{{$loop->index+1}} to {{$call->mobile}}
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapse{{$call->id}}" class="collapse"
                                         aria-labelledby="heading{{$call->id}}" data-parent="#accordion">
                                        <div class="card-body">
                                            {!! $call->notes !!} <br>
                                            @if($call->setAppointment)
                                                Appointment set for {{$call->appointment}}
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>Number has not yet been called.</p>
                    @endif

            </div>
        </div>
    </div>


</div>
