<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 17:22
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->push(t('Home'), route('home'));
});

Breadcrumbs::register('login', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Login'), route('login'));
});

Breadcrumbs::register('login.provider.email', function (Crumbs $breadcrumbs, \App\Entity\UserProvider $user) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Confirm email'), route('login.provider.email', $user));
});

Breadcrumbs::register('register', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Register'), route('register'));
});

Breadcrumbs::register('category.list', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('All categories'), route('category.list'));
});

Breadcrumbs::register('category.show', function (Crumbs $breadcrumbs, \App\Entity\Category $category) {
    if ($category->parent) {
        $breadcrumbs->parent('category.show', $category->parent);
    } else {
        $breadcrumbs->parent('category.list');
    }

    $breadcrumbs->push(t($category->name), route('category.show', $category->slug));
});

Breadcrumbs::register('password.request', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Form reset password'), route('password.request'));
});

Breadcrumbs::register('password.reset', function (Crumbs $breadcrumbs, $token) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Write new password'), route('password.reset', $token));
});