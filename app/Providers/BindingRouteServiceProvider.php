<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.06.2018
 * Time: 0:24
 */

namespace App\Providers;

use App\Entity\Category;
use App\Entity\Chat\Dialogs;
use App\Entity\Classroom\Classroom;
use App\Entity\TutorProfile;
use Illuminate\Support\ServiceProvider;

class BindingRouteServiceProvider extends ServiceProvider
{
    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        \Route::bind('category_slug', [$this, 'categorySlug']);
        \Route::bind('accessDialog', [$this, 'accessDialog']);
        \Route::bind('sendMessageDialog', [$this, 'sendMessageDialog']);
        \Route::bind('room', [$this, 'room']);
        \Route::bind('tutorProfile', [$this, 'tutorProfile']);
        \Route::bind('lessonActive', [$this, 'lessonActive']);
    }

    /**
     * Find category by alias
     *
     * @param string $value
     * @return Category
     */
    public function categorySlug($value)
    {
        return Category::where('slug', $value)->firstOrFail();
    }

    /**
     * Do user has access in dialog?
     *
     * @param int $value
     * @return int
     */
    public function accessDialog($value)
    {
        if (!(new Dialogs())->isAccess(intval($value), \Auth::id())) {
            abort(404);
        }

        return intval($value);
    }

    /**
     * Send message to dialog
     *
     * @param int $value
     * @return array
     */
    public function sendMessageDialog($value)
    {
        try {
            if (!$source = (new Dialogs())->isSendMessage(intval($value), \Auth::id())) {
                throw new \DomainException(t('You do not have rights to write in this dialog'));
            }
        } catch (\DomainException $e) {
            abort(404, $e->getMessage());
        }

        return $source;
    }

    /**
     * Is open dialog?
     *
     * @param int $value
     * @return Classroom
     */
    public function room($value)
    {
        /** @var Classroom $model */
        $model = Classroom::with(['tutorModel'])->findOrFail(intval($value));

        if (!$model->isAccessUser(\Auth::id())) {
            abort(404, t('You have not access'));
        }

        if (!$model->isViewRoom()) {
            abort(403, t('You can not open room now'));
        }

        return $model;
    }

    /**
     * Tutor is public page
     *
     * @param int $value
     * @return Classroom
     */
    public function tutorProfile($value)
    {
        $profile = \Cache::remember('tutor-profile-' . $value, 15, function () use ($value) {
            return TutorProfile::where('user_id', intval($value))
                ->with(['user' => function ($builder) {
                    $builder->with('image');
                }])
                ->first();
        });

        if (!($profile && $profile->isActive())) {
            abort(404, t('Not found profile'));
        }

        return $profile;
    }

    /**
     * Edit active classroom
     *
     * @param int $value
     * @return Classroom
     */
    public function lessonActive($value)
    {
        /** @var Classroom $classroom */
        $classroom = Classroom::findOrFail(intval($value));

        $tutor = \Auth::user()->tutor;

        if (!($tutor && $classroom->hasTutor($tutor->id)) || !$classroom->isActiveStatus() || $classroom->isStarting()) {
            abort(403, t('You can not edit lesson'));
        }

        return $classroom;
    }
}