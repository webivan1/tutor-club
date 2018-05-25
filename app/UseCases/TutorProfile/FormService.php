<?php

namespace App\UseCases\TutorProfile;

use App\Entity\ContentProfile;
use App\Entity\Files;
use App\Entity\TutorProfile;
use App\Events\Profile\TutorProfileEvent;
use App\Http\Requests\TutorProfile\CreateRequest;
use App\Http\Requests\TutorProfile\UpdateRequest;
use App\Jobs\ImagickJob;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;

class FormService
{
    /**
     * @param CreateRequest $request
     */
    public function create(CreateRequest $request): void
    {
        $this->registerEvent();

        /** @var Files $file */
        $file = $this->createFile(
            $this->uploadImage($request->file('photo'), 'images/upload')
        );

        if (empty($file)) {
            throw new \DomainException(t('home.errorUploadedFilesTutorForm'));
        }

        // Create profile
        $profile = $this->updateOrCreateProfile(
            $request->input('country_code'),
            $request->input('phone'),
            $request->input('gender'),
            $file->id
        );

        if (empty($profile)) {
            throw new \DomainException(t('home.errorCreatedTutorProfile'));
        }

        // Create content
        $this->updateOrCreateContent(
            $profile->id,
            $request->only('description', 'content')
        );
    }

    /**
     * @param UpdateRequest $request
     */
    public function update(UpdateRequest $request): void
    {
        $this->registerEvent();

        if (!$profile = TutorProfile::findModel()) {
            throw new \DomainException(t('home.ErrorUndefinedTutorProfile'));
        }

        if ($request->hasFile('photo')) {
            // delete old photo
            !$profile->image ?: $profile->image->delete();

            // create new photo
            $file = $this->createFile(
                $this->uploadImage($request->file('photo'), 'images/upload')
            );

            $profile->file_id = $file->id;
        }

        $profile->update($request->only('country_code', 'phone', 'gender'));

        \Event::listen('content-profile.change', function () use ($profile) {
            // trigger event updating TutorProfile
            $profile->update(['status' => TutorProfile::STATUS_DISABLED]);
        });

        $this->updateOrCreateContent(
            $profile->id,
            $request->only('description', 'content')
        );
    }

    /**
     * Register event for create & update profile
     *
     * @return void
     */
    private function registerEvent()
    {
        TutorProfile::observe(new TutorProfileEvent());
    }

    /**
     * @param UploadedFile $file
     * @param string $path
     * @param null|string $filename
     * @return false|UploadedFile|string
     */
    public function uploadImage(UploadedFile $file, string $path, ?string $filename = null)
    {
        // save image
        $pathLocalFile = !$filename
            ? \Storage::disk('public')->putFile($path, $file)
            : \Storage::disk('public')->putFileAs($path, $file, $filename);

        ImagickJob::dispatch($pathLocalFile, [
            '200x250', '300x350', '350', '400'
        ]);

//        $fileLocalObject = new UploadedFile(public_path($pathLocalFile), basename($pathLocalFile));
//
//        $pathStorage = !$filename ? $fileLocalObject->store($path) : $fileLocalObject->storeAs($path, $filename);
//
//        \Storage::disk('public')->delete($pathLocalFile);

        return $pathLocalFile;
    }

    /**
     * @param string $file
     * @return Model
     */
    public function createFile(string $file): Model
    {
        return Files::create([
            'filename' => basename($file),
            'file_path' => '/' . $file
        ]);
    }

    /**
     * @param string $country_code
     * @param string $phone
     * @param string $gender
     * @param int $file_id
     * @return Model
     */
    public function updateOrCreateProfile(string $country_code, string $phone, string $gender, int $file_id): Model
    {
        return TutorProfile::updateOrCreate([
            'user_id' => \Auth::id()
        ], [
            'country_code' => $country_code,
            'phone' => $phone,
            'gender' => $gender,
            'file_id' => $file_id,
        ]);
    }

    /**
     * @param int $profileId
     * @param array $requestData
     */
    public function updateOrCreateContent(int $profileId, array $requestData): void
    {
        $result = [];

        foreach ($requestData as $column => $items) {
            foreach ($items as $lang => $value) {
                $result[$lang][$column] = !$value ? null : $this->filterContent($value);
            }
        }

        foreach ($result as $lang => $attr) {
            ContentProfile::updateOrCreate([
                'profile_id' => $profileId,
                'lang' => $lang
            ], $attr);
        }
    }

    /**
     * @param string $content
     * @return string
     */
    private function filterContent(string $content): string
    {
        return strip_tags($content, '<p><strong><ul><li><br><i><em><a>');
    }
}