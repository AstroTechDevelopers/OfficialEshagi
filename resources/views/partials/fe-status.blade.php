<?php
/**
 *Created by PhpStorm for eshagi
 *User: Vincent Guyo
 *Date: 2020-12-15
 *Time: 4:57 AM
 */

?>
@if (session('message'))
    <div class="alert alert-{{ Session::get('status') }} status-box alert-dismissable fade show" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
        {{ session('message') }}
    </div>
@endif

@if (session('success'))
    <p class="text-success">{{ session('success') }}</p>
@endif

@if(session()->has('status'))
    @if(session()->get('status') == 'wrong')
        <div class="alert alert-danger status-box alert-dismissable fade show" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
            {{ session('message') }}
        </div>
        @else
        <div class="alert alert-success status-box alert-dismissable fade show" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
            {{ session('message') }}
        </div>
    @endif
@endif

@if (session('status'))
    <div class="alert alert-success">
        {{ session('status') }}
    </div>
@endif

@if (session('error'))
    <p class="text-danger">{{ session('error') }}</p>
@endif

@if (session('errors') && count($errors) > 0)
    <ul>
        @foreach ($errors->all() as $error)
            <li><p class="text-danger">{{ $error }}</p></li>
        @endforeach
    </ul>
@endif
