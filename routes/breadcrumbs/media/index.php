<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('media.show', function (Crumbs $breadcrumbs, \App\Entity\Media\Category $category) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t($category->title));
});

Breadcrumbs::register('media.material.show', function (Crumbs $breadcrumbs, \App\Entity\Media\News $news) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t($news->category->title), route('media.show', $news->category->slug));
    $breadcrumbs->push(t($news->title));
});