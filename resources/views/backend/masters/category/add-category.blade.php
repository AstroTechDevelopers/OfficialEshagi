@extends('layouts.app')

@section('template_title')
    Add Category
@endsection

@section('template_fastload_css')
@endsection

@section('template_linked_css')

    <link href="{{ asset('css/select2.min.css')}}" rel="stylesheet" />
@endsection

@section('content')
    <div class="page-title-box">
        <div class="container-fluid">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h4 class="page-title mb-1">Categories</h4>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{url('/categories')}}">Categories</a></li>
                        <li class="breadcrumb-item active">Create</li>
                    </ol>
                </div>

                <div class="col-md-4">
                    <div class="float-right d-none d-md-block">
                        <div>
                            <a class="btn btn-light btn-rounded" href="{{url('/categories')}}" type="button">
                                <i class="mdi mdi-keyboard-backspace mr-1"></i>Back to Categories
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- end page title end breadcrumb -->
    <div class="page-content-wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            {!! Form::open(array('route' => 'save-category', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('catname') ? ' has-error ' : '' }}">
                                {!! Form::label('catname', 'Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('catname', NULL, array('id' => 'catname', 'class' => 'form-control', 'placeholder' => 'e.g. Electronics')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="catname">
                                                <i class="fa fa-fw" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('catname'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('catname') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group has-feedback row {{ $errors->has('catslug') ? ' has-error ' : '' }}">
                                {!! Form::label('catslug', 'Slug', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        {!! Form::text('catslug', NULL, array('id' => 'catslug', 'class' => 'form-control', 'placeholder' => 'e.g. electronics')) !!}
                                        <div class="input-group-append">
                                            <label class="input-group-text" for="catslug">
                                                <i class="fa fa-fw" aria-hidden="true"></i>
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('catslug'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('catslug') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            {!! Form::button('Add', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('footer_scripts')

    <script src="{{ asset('js/select2.min.js')}}"></script>

    <script type="text/javascript">
        $("#role").select2({
            placeholder: 'Please select a system role.',
            allowClear:true,
        });

        $("#locale_id").select2({
            placeholder: 'Please select locale.',
            allowClear:true,
        });
    </script>

    <script>
        function validateId(){
            var myId=document.getElementById("natid").value;
            myId=myId.replace(/ /gi, "");
            myId=myId.replace(/-/gi, "");

            myId=insert(myId, "-", 2);
            myId=insert(myId, "-", myId.length-3);
            myId=insert(myId, "-", myId.length-2);
            console.log(myId);

            document.getElementById("natid").value=myId;
        }
        function insert(main_string, ins_string, pos) {
            if(typeof(pos) == "undefined") {
                pos = 0;
            }
            if(typeof(ins_string) == "undefined") {
                ins_string = '';
            }
            return main_string.slice(0, pos) + ins_string + main_string.slice(pos);
        }
    </script>

@endsection
