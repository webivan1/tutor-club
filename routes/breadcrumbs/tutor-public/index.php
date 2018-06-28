<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.06.2018
 * Time: 0:49
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;
use App\Entity\TutorProfile;

Breadcrumbs::register('tutor.view', function (Crumbs $breadcrumbs, TutorProfile $tutorProfile) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push($tutorProfile->user->name, route('tutor.view', $tutorProfile));
});