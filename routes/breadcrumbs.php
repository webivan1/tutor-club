<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 01.04.2018
 * Time: 18:34
 */

use DaveJamesMiller\Breadcrumbs\BreadcrumbsGenerator as Crumbs;
use App\Entity\Admin\Keywords;

Breadcrumbs::register('home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->push(t('Home'), route('home'));
});

Breadcrumbs::register('category.list', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push(t('All categories'), route('category.list'));
});

Breadcrumbs::register('category.show', function (Crumbs $breadcrumbs, \App\Entity\Category $category) {
    if ($category->parent) {
        $breadcrumbs->parent('category.show', $category->parent);
    } else {
        $breadcrumbs->parent('category.list');
    }

    $breadcrumbs->push(t($category->name), route('category.show', $category->slug));
});

/** Cabinet */
Breadcrumbs::register('cabinet.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Кабинет', route('cabinet.home'));
});

/** Profile */
Breadcrumbs::register('profile.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.home');
    $breadcrumbs->push('Профиль', route('profile.home'));
});
Breadcrumbs::register('profile.email.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push('Форма смены email', route('profile.email.form'));
});
Breadcrumbs::register('profile.email.send', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.email.form');
    $breadcrumbs->push('Подтверждение почты', route('profile.email.send'));
});

Breadcrumbs::register('profile.password.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push('Смена пароля', route('profile.password.form'));
});
Breadcrumbs::register('profile.edit.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push('Изменить данные', route('profile.edit.form'));
});

Breadcrumbs::register('profile.tutor.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.home');
    $breadcrumbs->push('Профиль репетитора', route('profile.tutor.home'));
});
Breadcrumbs::register('profile.tutor.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push('Форма профиля', route('profile.tutor.form'));
});
Breadcrumbs::register('profile.tutor.verify.form', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push('Подтверждение телефона', route('profile.tutor.verify.form'));
});

/** Auth */
Breadcrumbs::register('login', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Авторизация', route('login'));
});
Breadcrumbs::register('register', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('home');
    $breadcrumbs->push('Регистрация', route('register'));
});

// Advert
Breadcrumbs::register('cabinet.advert.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('profile.tutor.home');
    $breadcrumbs->push('Список объявлений', route('cabinet.advert.index'));
});
Breadcrumbs::register('cabinet.advert.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push('Выбрать категорию', route('cabinet.advert.create'));
});
Breadcrumbs::register('cabinet.advert.create.end', function (Crumbs $breadcrumbs, \App\Entity\Category $category) {
    $breadcrumbs->parent('cabinet.advert.create');
    $breadcrumbs->push('Добавить объявление', route('cabinet.advert.create.end', $category));
});
Breadcrumbs::register('cabinet.advert.update', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push('Редактирование объявления', route('cabinet.advert.update', $advert));
});
Breadcrumbs::register('cabinet.advert.update.prices', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push('Цены', route('cabinet.advert.update.prices', $advert));
});
Breadcrumbs::register('cabinet.advert.update.files', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push('Фото', route('cabinet.advert.update.files', $advert));
});
Breadcrumbs::register('cabinet.advert.update.attribute', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push('Атрибуты', route('cabinet.advert.update.attribute', $advert));
});
Breadcrumbs::register('cabinet.advert.moderation', function (Crumbs $breadcrumbs, \App\Entity\Advert\Advert $advert) {
    $breadcrumbs->parent('cabinet.advert.index');
    $breadcrumbs->push($advert->title);
    $breadcrumbs->push('Ошибка', route('cabinet.advert.moderation', $advert));
});


/** Admin panel */
Breadcrumbs::register('cabinet.admin.lang.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Языки', route('cabinet.admin.lang.index'));
});
Breadcrumbs::register('cabinet.admin.lang.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.lang.index');
    $breadcrumbs->push('Добавить язык', route('cabinet.admin.lang.create'));
});
Breadcrumbs::register('cabinet.admin.lang.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Lang $lang) {
    $breadcrumbs->parent('cabinet.admin.lang.index');
    $breadcrumbs->push('Редактировать: ' . $lang->name, route('cabinet.admin.lang.edit', $lang));
});

Breadcrumbs::register('cabinet.admin.translate.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Переводы', route('cabinet.admin.translate.index'));
});
Breadcrumbs::register('cabinet.admin.translate.create', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.translate.index');
    $breadcrumbs->push('Добавить перевод', route('cabinet.admin.translate.create'));
});
Breadcrumbs::register('cabinet.admin.translate.edit', function (Crumbs $breadcrumbs, Keywords $translate) {
    $breadcrumbs->parent('cabinet.admin.translate.index');
    $breadcrumbs->push('Редактировать: ' . $translate->name, route('cabinet.admin.translate.edit', $translate));
});

// Category
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

/** Admin panel */
Breadcrumbs::register('cabinet.admin.home', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.home');
    $breadcrumbs->push('Админка', route('cabinet.admin.home'));
});

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

// User manager
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

// Tutor profile
Breadcrumbs::register('cabinet.admin.tutor.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список репетиторов', route('cabinet.admin.tutor.index'));
});
Breadcrumbs::register('cabinet.admin.tutor.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\TutorProfile $tutor) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Профиль репетитора #' . $tutor->id, route('cabinet.admin.tutor.edit', $tutor));
});

// Advert admin
Breadcrumbs::register('cabinet.admin.advert.index', function (Crumbs $breadcrumbs) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Список объявлений', route('cabinet.admin.advert.index'));
});
Breadcrumbs::register('cabinet.admin.advert.edit', function (Crumbs $breadcrumbs, \App\Entity\Admin\Advert $advert) {
    $breadcrumbs->parent('cabinet.admin.home');
    $breadcrumbs->push('Редактировать объявление #' . $advert->id, route('cabinet.admin.advert.edit', $advert));
});