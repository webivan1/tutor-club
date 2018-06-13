<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 12.06.2018
 * Time: 16:35
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('classroom.home', function (Crumbs $breadcrumbs, $room) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('Classroom'), route('classroom.home', $room));
});