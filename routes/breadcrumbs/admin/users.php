<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:16
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.users.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список пользователей', route('cabinet.admin.users.index'));
});

Breadcrumbs::register('cabinet.admin.users.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.users.index');
    $breadcrumbs->push('Добавить пользователя', route('cabinet.admin.users.create'));
});

Breadcrumbs::register('cabinet.admin.users.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\User $user) {
    $breadcrumbs->parent('cabinet.admin.users.index');
    $breadcrumbs->push('Редактировать: ' . $user->name, route('cabinet.admin.users.edit', $user));
});