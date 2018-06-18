<?php

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.media.category.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Категории новостей', route('cabinet.admin.media.category.index'));
});

Breadcrumbs::register('cabinet.admin.media.category.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.media.category.index');
    $breadcrumbs->push('Добавить категорию новости', route('cabinet.admin.media.category.create'));
});

Breadcrumbs::register('cabinet.admin.media.category.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Media\Category $category) {
    $breadcrumbs->parent('cabinet.admin.media.category.index');
    $breadcrumbs->push('Редактировать: ' . $category->title, route('cabinet.admin.media.category.edit', $category));
});

Breadcrumbs::register('cabinet.admin.media.news.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Новости', route('cabinet.admin.media.news.index'));
});

Breadcrumbs::register('cabinet.admin.media.news.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.media.news.index');
    $breadcrumbs->push('Добавить новость', route('cabinet.admin.media.news.create'));
});

Breadcrumbs::register('cabinet.admin.media.news.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Media\News $news) {
    $breadcrumbs->parent('cabinet.admin.media.news.index');
    $breadcrumbs->push('Редактировать: ' . $news->heading, route('cabinet.admin.media.news.edit', $news));
});