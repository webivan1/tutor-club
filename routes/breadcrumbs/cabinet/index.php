<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 17:46
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Cabinet'), route('cabinet.home'));
});