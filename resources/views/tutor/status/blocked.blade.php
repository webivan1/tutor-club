<div class="text-center pt-5">
    <p>{{ t('Your profile has been blocked by the administration') }}</p>

    <div class="row justify-content-md-center">
        <div class="col-md-4 text-left alert alert-danger">
            {{ $profile->comment }}
        </div>
    </div>
</div>