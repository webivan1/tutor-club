<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:17
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.tutor.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список репетиторов', route('cabinet.admin.tutor.index'));
});

Breadcrumbs::register('cabinet.admin.tutor.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\TutorProfile $tutor) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Профиль репетитора #' . $tutor->id, route('cabinet.admin.tutor.edit', $tutor));
});