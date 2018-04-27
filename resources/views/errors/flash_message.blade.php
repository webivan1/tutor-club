@if(Session::has('success'))
    <div class="alert alert-success my-2">
        {!! session('success') !!}
    </div>
@endif

@if(Session::has('info'))
    <div class="alert alert-info my-2">
        {!! session('info') !!}
    </div>
@endif

@if(Session::has('warning'))
    <div class="alert alert-warning my-2">
        {!! session('warning') !!}
    </div>
@endif

@if(Session::has('danger'))
    <div class="alert alert-danger my-2">
        {!! session('danger') !!}
    </div>
@endif

@if(Session::has('error'))
    <div class="alert alert-danger my-2">
        {!! session('error') !!}
    </div>
@endif