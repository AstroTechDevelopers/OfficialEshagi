@if (session('message'))
  <div class="alert alert-{{ Session::get('status') }} status-box alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
    {{ session('message') }}
  </div>
@endif

@if (session('success'))
  <div class="alert alert-success alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4><div class="icons-l uim-icon-success">
            <i class="uim uim-check-circle" aria-hidden="true"></i>Success
        </div>
        </h4>
    {{ session('success') }}
  </div>
@endif

@if(session()->has('status'))
    @if(session()->get('status') == 'wrong')
        <div class="alert alert-danger status-box alert-dismissable fade show" role="alert">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;<span class="sr-only">Close</span></a>
            {{ session('message') }}
        </div>
    @endif
@endif

@if (session('error'))
  <div class="alert alert-danger alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>
        <div class="icons-l uim-icon-danger">
            <i class="uim uim-times-circle" aria-hidden="true"></i>Error
        </div>

    </h4>
    {{ session('error') }}
  </div>
@endif

@if ( request()->is('home') && auth()->user()->utype == 'Client' && is_null(\App\Models\Client::where('natid', auth()->user()->natid)->first()))
    <div class="alert alert-warning alert-dismissable fade show" role="alert">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        <h4>
            <div class="icons-l uim-icon-danger">
                <i class="uim uim-times-info" aria-hidden="true"></i>Error
            </div>

        </h4>
        <p>We have noticed that your account is pending some kyc documentations, please click <a href="/quickly-continue">here</a> to continue</p>
    </div>
@endif

@if (session('errors') && count($errors) > 0)
  <div class="alert alert-danger alert-dismissable fade show" role="alert">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <h4>
        <div class="icons-l uim-icon-danger">
            <i class="uim uim-times-circle" aria-hidden="true"></i><strong>{{ Lang::get('auth.whoops') }}</strong> {{ Lang::get('auth.someProblems') }}
        </div>
    </h4>
    <ul>
      @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
      @endforeach
    </ul>
  </div>
@endif
