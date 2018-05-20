<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 16.05.2018
 * Time: 22:27
 */

namespace App\Http\Controllers\Chat;

use App\Entity\Chat\DialogsSearch;
use App\Entity\Chat\DialogUsers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

# Листинг с пагинацией
# По дате обновления
#
#
#
class ListController extends Controller
{
    /**
     * Pagination list dialogs
     *
     * @param DialogsSearch $model
     * @param Request $request
     * @return LengthAwarePaginator
     */
    public function index(DialogsSearch $model, Request $request)
    {
        return $model->listData(
            \Auth::id(), $request->all(), $request->url(), (int) $request->get('page', 1)
        );
    }
}