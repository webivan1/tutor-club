<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:14
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.category.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Категории', route('cabinet.admin.category.index'));
});

Breadcrumbs::register('cabinet.admin.category.show', function (Crumbs $breadcrumbs, \App\Entity\Admin\Category $cat) {
    $cat->parent
        ? $breadcrumbs->parent('cabinet.admin.category.show', $cat->parent)
        : $breadcrumbs->parent('cabinet.admin.category.index');
    $breadcrumbs->push($cat->name, route('cabinet.admin.category.show', $cat));
});

Breadcrumbs::register('cabinet.admin.category.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.category.index');
    $breadcrumbs->push('Добавить категорию', route('cabinet.admin.category.create'));
});

Breadcrumbs::register('cabinet.admin.category.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Category $cat) {
    $breadcrumbs->parent('cabinet.admin.category.index');
    $breadcrumbs->push('Редактировать: ' . $cat->name, route('cabinet.admin.category.edit', $cat));
});

Breadcrumbs::register('cabinet.admin.category.attribute.create', function (Crumbs $breadcrumbs, \App\Entity\Admin\Category $cat) {
    $breadcrumbs->parent('cabinet.admin.category.show', $cat);
    $breadcrumbs->push('Добавить атрибут', route('cabinet.admin.category.attribute.create', $cat));
});

Breadcrumbs::register('cabinet.admin.category.attribute.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Category $cat, \App\Entity\Attribute $attribute) {
    $breadcrumbs->parent('cabinet.admin.category.show', $cat);
    $breadcrumbs->push('Обновить атрибут', route('cabinet.admin.category.attribute.edit', [$cat, $attribute]));
});