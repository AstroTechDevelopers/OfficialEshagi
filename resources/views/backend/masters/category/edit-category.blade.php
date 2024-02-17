@extends('layouts.app')

@section('template_title')
    Update Category
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
                        <li class="breadcrumb-item active">Editing Category: {{$category->category_name}}</li>
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
                            {!! Form::open(array('route' => 'update-category', 'method' => 'POST', 'role' => 'form', 'class' => 'needs-validation')) !!}

                            {!! csrf_field() !!}

                            <div class="form-group has-feedback row {{ $errors->has('catname') ? ' has-error ' : '' }}">
                                {!! Form::label('catname', 'Name', array('class' => 'col-md-3 control-label')); !!}
                                <div class="col-md-9">
                                    <div class="input-group">
                                        <input type="text" name="catname" class="form-control" value="{{ $category->category_name }}" required>
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
                                        <input type="text" name="catslug" class="form-control" value="{{ $category->category_slug }}" required>
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
							<input type="hidden" name="catid" value="{{ $category->id }}">
                            {!! Form::button('Update', array('class' => 'btn btn-success margin-bottom-1 mb-1 float-right','type' => 'submit' )) !!}
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
@endsection
