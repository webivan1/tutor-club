<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;
use App\Entity\Classroom\Classroom;

Breadcrumbs::register('profile.lesson.list.active', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Lessons is active'), route('profile.lesson.list.active'));
});

Breadcrumbs::register('profile.lesson.list.pending', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Lessons is pending'), route('profile.lesson.list.pending'));
});

Breadcrumbs::register('profile.lesson.list.disabled', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Lessons is disabled'), route('profile.lesson.list.disabled'));
});

Breadcrumbs::register('profile.lesson.list.closed', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Lessons is closed'), route('profile.lesson.list.closed'));
});

Breadcrumbs::register('profile.lesson.edit.active', function (Crumbs $breadcrumbs, Classroom $lessonActive) {
    $breadcrumbs->parent('profile.lesson.list.active');
    $breadcrumbs->push(t('Edit lesson'), route('profile.lesson.edit.active', $lessonActive));
});