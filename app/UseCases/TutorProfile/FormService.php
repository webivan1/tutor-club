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

        // Create profile
        $profile = $this->updateOrCreateProfile(
            $request->input('country_code'),
            $request->input('phone'),
            $request->input('gender')
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
     * @param string $country_code
     * @param string $phone
     * @param string $gender
     * @return Model
     */
    public function updateOrCreateProfile(string $country_code, string $phone, string $gender): Model
    {
        return TutorProfile::updateOrCreate([
            'user_id' => \Auth::id()
        ], [
            'country_code' => $country_code,
            'phone' => $phone,
            'gender' => $gender
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
        return $content;
    }
}