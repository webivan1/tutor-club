<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 17.05.2018
 * Time: 23:24
 */

namespace App\Http\Controllers\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\DialogUsers;
use App\Entity\Chat\Messages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateDialogRequest;
use App\Http\Requests\Chat\ExistDialogRequest;
use App\UseCases\Chat\CreateDialogService;

class DialogController extends Controller
{
    /**
     * @var CreateDialogService
     */
    private $service;

    /**
     * DialogController constructor.
     * @param CreateDialogService $service
     */
    public function __construct(CreateDialogService $service)
    {
        $this->service = $service;
    }

    /**
     * @param CreateDialogRequest $request
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Symfony\Component\HttpFoundation\Response
     */
    public function create(CreateDialogRequest $request)
    {
        try {
            $this->service->run(
                $request->input('title'),
                $request->input('message'),
                \Auth::id(),
                $request->input('to_id')
            );
        } catch (\DomainException $e) {
            return response([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        return [
            'status' => 'success',
            'message' => t('You have successfully sent a message')
        ];
    }

    /**
     * Проверка существования диалога
     *
     * @param ExistDialogRequest $request
     * @param Dialogs $dialogs
     * @return array
     */
    public function exist(ExistDialogRequest $request, Dialogs $dialogs)
    {
        $users = array_unique([$request->input('user'), \Auth::id()]);

        if (count($users) < 2) {
            return response(t('Error'), 422);
        }

        $dialog = $dialogs->existDialog(...$users);

        return !$dialog ? ['status' => 'no'] : [
            'status' => 'yes',
            'dialog' => $dialog
        ];
    }

    /**
     * Close dialog
     *
     * @param Dialogs $dialog
     * @return string
     */
    public function close(Dialogs $dialog)
    {
        DialogUsers::down($dialog->id, \Auth::id());

        return 'ok';
    }
}