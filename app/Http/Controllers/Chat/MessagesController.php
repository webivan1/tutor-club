<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 17.05.2018
 * Time: 9:47
 */

namespace App\Http\Controllers\Chat;

use App\Entity\Chat\Dialogs;
use App\Entity\Chat\DialogUsers;
use App\Entity\Chat\Messages;
use App\Http\Controllers\Controller;
use App\Http\Requests\Chat\CreateMessageRequest;
use Illuminate\Http\Request;

class MessagesController extends Controller
{
    /**
     * List messages
     *
     * @param int $accessDialog
     * @param Messages $messages
     * @return array
     */
    public function index($accessDialog, Messages $messages)
    {
        DialogUsers::up($accessDialog, \Auth::id());

        return $messages->listData($accessDialog, 25);
    }

    /**
     * Create message
     *
     * @param int $sendMessageDialog
     * @param CreateMessageRequest $request
     * @param Messages $messages
     * @return array
     */
    public function create($sendMessageDialog, CreateMessageRequest $request, Messages $messages)
    {
        $message = Messages::new(
            $request->input('message'), $sendMessageDialog, \Auth::id()
        );

        return $message->getItem($message->id);
    }
}