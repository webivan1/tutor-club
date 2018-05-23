<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 17.05.2018
 * Time: 23:31
 */

namespace App\UseCases\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\Messages;
use App\Events\Chat\ChangeDialog;
use App\Events\Chat\CreateDialog;
use App\Events\Chat\SendMessage;

class CreateDialogService
{
    /**
     * Запуск создания диалога
     *
     * @param string $title
     * @param string $message
     * @param int $fromId
     * @param int $toId
     * @return void
     */
    public function run(string $title, string $message, int $fromId, int $toId): void
    {
        $isNewDialog = false;

        if (!$dialog = $this->exist([$fromId, $toId])) {
            $isNewDialog = true;
            $dialog = $this->createDialog($title, $fromId, $toId);
        }

        $this->createMessage($dialog, $message, $fromId, $isNewDialog);
    }

    /**
     * Проверка существования диалога
     *
     * @param $users
     * @return Dialogs|null
     */
    public function exist($users): ?Dialogs
    {
        $usersId = array_unique(array_values($users));

        if (count($usersId) < 2) {
            throw new \DomainException(t('You can not send a message to yourself'));
        }

        $dialog = new Dialogs();

        if ($model = $dialog->existDialog($usersId)) {
            return Dialogs::where('id', $model)->firstOrFail();
        }

        return null;
    }

    /**
     * Создаем диалог
     *
     * @param string $title
     * @param int $fromId
     * @param int $toId
     * @return Dialogs
     */
    public function createDialog(string $title, int $fromId, int $toId): Dialogs
    {
        return \DB::transaction(function () use ($title, $fromId, $toId) {
            Dialogs::saved(function (Dialogs $model) use ($fromId, $toId) {
                $model->users()->create(['user_id' => $fromId]);
                $model->users()->create([
                    'visited_at' => now()->format('Y-m-d H:i:s'),
                    'user_id' => $toId
                ]);
            });

            $model = Dialogs::new($title, $fromId);

            return $model;
        });
    }

    /**
     * Создаем сообщение
     *
     * @param Dialogs $model
     * @param string $message
     * @param int $fromId
     * @param bool $newDialog
     * @return void
     */
    public function createMessage(Dialogs $model, string $message, int $fromId, bool $newDialog): void
    {
        if ($model->isClose()) {
            throw new \DomainException(t('Dialog is closed'));
        }

        Messages::created(function (Messages $messages) use ($model, $newDialog) {
            // add elastic
            event(new ChangeDialog($model, ChangeDialog::EVENT_CREATE));
            !$newDialog ?: event((new CreateDialog($model)));

            event((new SendMessage($messages))->delay(now()->addSeconds(5)));
        });

        // first message
        $model->messages()->create([
            'user_id' => $fromId,
            'message' => $message
        ]);
    }
}