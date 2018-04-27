@if (count($errors) > 0)
    <div class="alert alert-danger my-2">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li><b>{{ $error }}</b></li>
            @endforeach
        </ul>
    </div>
@endif