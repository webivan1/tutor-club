<?php

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Оналйн юзер
Broadcast::channel('online', function () {
    return true;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return true;
});

// Добавление нового диалога, уведомляем пользователей
Broadcast::channel('add.dialog.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Получение сообщений
Broadcast::channel('dialog.{id}', function ($user, $id) {
    return (bool) (new \App\Entity\Chat\Dialogs())->isAccess($id, $user->id);
});

Broadcast::channel('send.message.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('classroom.close.{id}', function ($user, $id) {
    return (bool) \App\Entity\Classroom\ClassroomUser::where('classroom_id', (int)$id)
        ->where('user_id', $user->id)
        ->first();
});