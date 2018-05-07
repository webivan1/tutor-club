<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 17:52
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('profile.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.home');
    $breadcrumbs->push(t('Profile'), route('profile.home'));
});

Breadcrumbs::register('profile.email.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Form change email'), route('profile.email.form'));
});

Breadcrumbs::register('profile.email.send', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.email.form');
    $breadcrumbs->push(t('Confirm mail'), route('profile.email.send'));
});

Breadcrumbs::register('profile.password.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('Change Password'), route('profile.password.form'));
});

Breadcrumbs::register('profile.edit.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push(t('To change the data'), route('profile.edit.form'));
});