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