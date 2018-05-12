<div class="card-body">
    <div class="text-center py-3 row justify-content-center">
        <a href="{{ route('login.provider', 'google') }}" class="d-block item-icon-soc col-auto">
            <i class="fab fa-google"></i>
        </a>
        <a href="{{ route('login.provider', 'github') }}" class="d-block item-icon-soc col-auto">
            <i class="fab fa-github"></i>
        </a>
        <a href="{{ route('login.provider', 'facebook') }}" class="d-block item-icon-soc col-auto">
            <i class="fab fa-facebook"></i>
        </a>
    </div>
</div>

<div class="container-fluid">
    <div class="row">
        <div class="col border-line-bottom"></div>
        <div class="col-auto">
            <h4><b class="text-muted">{{ t('Or') }}</b></h4>
        </div>
        <div class="col border-line-bottom"></div>
    </div>
</div>