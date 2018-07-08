<?php
/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 08.07.2018
 * Time: 18:01
 */

namespace App\UseCases\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\Messages;
use App\Entity\Classroom\Classroom;
use App\Entity\Classroom\ClassroomUser;
use App\Events\Chat\CreateDialog;
use App\Events\Chat\SendMessageArray;

class SendInviteLesson
{
    /**
     * @var CreateDialogService
     */
    private $createDialogService;

    /**
     * SendInviteLesson constructor.
     * @param CreateDialogService $service
     */
    public function __construct(CreateDialogService $service)
    {
        $this->createDialogService = $service;
    }

    /**
     * @param int $from
     * @param Classroom $classroom
     * @return bool
     */
    public function run(int $from, Classroom $classroom): bool
    {
        $users = $classroom->users()->where('status', ClassroomUser::STATUS_DISABLED)->get();

        if (empty($users)) {
            return false;
        }

        foreach ($users as $user) {
            $this->send($classroom, $from, $user->user_id);
        }

        return true;
    }

    /**
     * @param Classroom $classroom
     * @param int $from
     * @param int $to
     */
    public function send(Classroom $classroom, int $from, int $to)
    {
        if (!$dialog = $this->createDialogService->exist([$from, $to])) {
            $dialog = $this->createDialogService->createDialog($classroom->subject, $from, $to);
            event((new CreateDialog($dialog)));
        }

        $this->createMessage($classroom, $dialog, $from, $to);
    }

    /**
     * @param Classroom $classroom
     * @param Dialogs $dialog
     * @param int $from
     * @param int $to
     */
    public function createMessage(Classroom $classroom, Dialogs $dialog, int $from, int $to)
    {
        $message = Messages::newInvite($dialog->id, $from, $classroom);
        $messageItem = $message->getItem($message->id);

        $event = new SendMessageArray($messageItem, [$from, $to]);

        event($event->onQueue(Messages::QUEUE_NAME));
    }
}