<div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">Query Details</h4>
                    <p class="card-title-desc">New Query. </p>

                    {!! Form::open(array('route' => 'queries.store', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                    {!! csrf_field() !!}
                    <div class="form-group has-feedback row {{ $errors->has('medium') ? ' has-error ' : '' }}">
                        {!! Form::label('medium', 'Medium(How did the query come in)*', array('class' => 'col-md-12 control-label')); !!}
                        <div wire:ignore class="col-md-12">
                            <select class="form-control" name="medium" id="medium" required>
                                <option value="">Select Medium</option>
                                <option value="WhatsApp">WhatsApp </option>
                                <option value="Email">Email </option>
                                <option value="Phone">Phone </option>
                                <option value="SMS">SMS </option>
                                <option value="Walk-in">Walk-in </option>
                                <option value="Other">Other </option>
                            </select>
                            @if ($errors->has('medium'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('medium') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('natid') ? ' has-error ' : '' }}">
                        {!! Form::label('natid', 'National ID*', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <input wire:click="changeEvent($event.target.value)" wire:model="natid" class="form-control" type="text" placeholder="e.g. 45-234567-H-90" value="{{ old('natid') }}" id="natid" name="natid" required>
                            </div>
                            @if ($errors->has('natid'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('natid') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('first_name') ? ' has-error ' : '' }}">
                        {!! Form::label('first_name',' First Name*', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="e.g. John" value="{{ $client->first_name ?? old('first_name') }}" id="first_name" name="first_name" required>
                            </div>
                            @if ($errors->has('first_name'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('first_name') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('last_name') ? ' has-error ' : '' }}">
                        {!! Form::label('last_name', 'Last Name*', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="e.g. Doe" value="{{ $client->last_name ?? old('last_name') }}" id="last_name" name="last_name" required>
                            </div>
                            @if ($errors->has('last_name'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('last_name') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('mobile') ? ' has-error ' : '' }}">
                        {!! Form::label('mobile', 'Mobile Number*', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                <input class="form-control" type="text" placeholder="e.g. 0773 418 009" value="{{ $client->mobile ?? old('mobile') }}" id="mobile" name="mobile" required>
                            </div>
                            @if ($errors->has('mobile'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('mobile') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group has-feedback row {{ $errors->has('query') ? ' has-error ' : '' }}">
                        {!! Form::label('query', 'Query Details', array('class' => 'col-md-3 control-label')); !!}
                        <div class="col-md-9">
                            <div class="input-group">
                                {!! Form::textarea('query', NULL, array('id' => 'query', 'class' => 'form-control', 'placeholder' => 'e.g. Query details go here.' , 'required')) !!}
                            </div>
                            @if ($errors->has('query'))
                                <span class="help-block">
                                            <strong>{{ $errors->first('query') }}</strong>
                                        </span>
                            @endif
                        </div>
                    </div>

                    <br><br>
                    {!! Form::button('Log Query', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                    {!! Form::close() !!}

                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card">
                <div class="card-body">

                    <h4 class="header-title">Query Info </h4>
                    <p class="card-title-desc">Query related Info  </p>

                    @if($queries)
                        <div id="accordion">
                            @foreach($queries as $query)
                                <div class="card mb-0">
                                    <div class="card-header" id="heading{{$loop->index}}">
                                        <h5 class="m-0 font-size-14">
                                            <a data-toggle="collapse" data-parent="#accordion"
                                               href="#collapse{{$query->id}}" aria-expanded="true"
                                               aria-controls="collapse{{$loop->index}}" class="text-dark">
                                                Query #{{$loop->index+1}} by {{$query->agent}}
                                            </a>
                                        </h5>
                                    </div>

                                    <div id="collapse{{$query->id}}" class="collapse"
                                         aria-labelledby="heading{{$query->id}}" data-parent="#accordion">
                                        <div class="card-body">
                                            {!! $query->query !!} <br>
                                            Query came via {{$query->medium}}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p>No existing queries found.</p>
                    @endif

                </div>
            </div>
        </div>

    </div>
