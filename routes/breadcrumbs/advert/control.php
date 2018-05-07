<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 07.05.2018
 * Time: 17:52
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;

Breadcrumbs::register('cabinet.advert.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push(t('List of ads'), route('cabinet.advert.index'));
});

Breadcrumbs::register('cabinet.advert.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push(t('Select category'), route('cabinet.advert.create'));
});

Breadcrumbs::register('cabinet.advert.create.end', function (Crumbs $breadcrumbs, \App\Entity\Category $category) {
    $breadcrumbs->parent('cabinet.advert.create');
    $breadcrumbs->push(t('To add an advert'), route('cabinet.advert.create.end', $category));
});

Breadcrumbs::register('cabinet.advert.update', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push(t('Edit ads'), route('cabinet.advert.update', $advert));
});

Breadcrumbs::register('cabinet.advert.update.prices', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push(t('Price'), route('cabinet.advert.update.prices', $advert));
});

Breadcrumbs::register('cabinet.advert.update.files', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push(t('Photo'), route('cabinet.advert.update.files', $advert));
});

Breadcrumbs::register('cabinet.advert.update.attribute', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push(t('Attributes'), route('cabinet.advert.update.attribute', $advert));
});

Breadcrumbs::register('cabinet.advert.moderation', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push(t('Error'), route('cabinet.advert.moderation', $advert));
});