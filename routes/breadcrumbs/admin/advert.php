<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 18:17
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.admin.advert.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список объявлений', route('cabinet.admin.advert.index'));
});

Breadcrumbs::register('cabinet.admin.advert.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Advert $advert) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Редактировать объявление #' . $advert->id, route('cabinet.admin.advert.edit', $advert));
});