<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:16
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

// Permission
Breadcrumbs::register('cabinet.admin.permission.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список разрешений', route('cabinet.admin.permission.index'));
});

Breadcrumbs::register('cabinet.admin.permission.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.permission.index');
    $breadcrumbs->push('Добавить разрешение', route('cabinet.admin.permission.create'));
});

Breadcrumbs::register('cabinet.admin.permission.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Permission $permission) {
    $breadcrumbs->parent('cabinet.admin.permission.index');
    $breadcrumbs->push('Редактировать: ' . $permission->title, route('cabinet.admin.permission.edit', $permission));
});

// Roles
Breadcrumbs::register('cabinet.admin.role.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список ролей', route('cabinet.admin.role.index'));
});

Breadcrumbs::register('cabinet.admin.role.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.role.index');
    $breadcrumbs->push('Добавить роль', route('cabinet.admin.role.create'));
});

Breadcrumbs::register('cabinet.admin.role.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Role $role) {
    $breadcrumbs->parent('cabinet.admin.role.index');
    $breadcrumbs->push('Редактировать: ' . $role->title, route('cabinet.admin.role.edit', $role));
});