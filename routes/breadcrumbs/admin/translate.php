<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:13
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.translate.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Переводы', route('cabinet.admin.translate.index'));
});

Breadcrumbs::register('cabinet.admin.translate.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.translate.index');
    $breadcrumbs->push('Добавить перевод', route('cabinet.admin.translate.create'));
});

Breadcrumbs::register('cabinet.admin.translate.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Keywords $translate) {
    $breadcrumbs->parent('cabinet.admin.translate.index');
    $breadcrumbs->push('Редактировать: ' . $translate->name, route('cabinet.admin.translate.edit', $translate));
});