<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 17:57
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('profile.tutor.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Profile of the tutor'), route('profile.tutor.home'));
});

Breadcrumbs::register('profile.tutor.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push(t('Profile form'), route('profile.tutor.form'));
});

Breadcrumbs::register('profile.tutor.verify.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push(t('Verify your phone'), route('profile.tutor.verify.form'));
});