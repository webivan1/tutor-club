<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:15
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.home');
    $breadcrumbs->push('Админка', route('cabinet.admin.home'));
});