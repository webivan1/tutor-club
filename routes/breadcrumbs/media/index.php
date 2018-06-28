<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('media.show', function (Crumbs $breadcrumbs, \App\Entity\Media\Category $category) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t($category->title));
});

