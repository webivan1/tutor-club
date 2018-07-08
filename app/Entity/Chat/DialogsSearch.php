<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 17.05.2018
 * Time: 16:24
 */

namespace App\Entity\Chat;

use App\Entity\TutorProfile;
use App\Services\ElasticSearch\ElasticSearchModel;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Database\Query\Expression;
use App\Search\Chat\Dialogs as DialogSearchModel;
use Illuminate\Database\Query\JoinClause;
use Illuminate\Pagination\LengthAwarePaginator;

class DialogsSearch extends Dialogs
{
    /**
     * Listing dialogs by user
     *
     * @param int $userId
     * @param array $searchAttributes
     * @param string $path
     * @param int $page
     * @param int $pageSize
     * @return LengthAwarePaginator
     */
    public function listData(int $userId, array $searchAttributes = [], string $path, int $page = 1, int $pageSize = 12)
    {
        $model = self::initElasticModel();
        $model->setPagination($pageSize, $page);

        $model->setCustomQuery([
            'bool' => [
                'must' => [
                    ['term' => ['user_ids' => $userId]],
                ]
            ]
        ]);

        $model->setOrderBy([
            'updated_at' => ['order' => 'DESC']
        ]);

        $search = new DialogSearchModel($model);
        $search->search($searchAttributes);

        $total = (int) $model->queryTotal();

        if ($total === 0) {
            return new LengthAwarePaginator([], $total, $pageSize, $page);
        }

        $models = array_map(function ($model) use ($userId) {
            return $this->mappingModel($model, $userId);
        }, $model->querySource());

        return new LengthAwarePaginator($models, $total, $pageSize, $page, [
            'path' => $path
        ]);
    }

    /**
     * First dialog by ID
     *
     * @param int $id
     * @param int|null $userId
     * @return array
     */
    public function getItem(int $id, ?int $userId = null)
    {
        $model = self::initElasticModel();

        $model->setCustomQuery([
            'bool' => [
                'must' => array_filter([
                    ['term' => ['id' => $id]],
                    !$userId ? false : ['term' => ['user_ids' => $userId]]
                ])
            ]
        ]);

        if ($model->queryTotal() > 0) {
            $models = $model->querySource();
        } else {
            // Если не успел записаться в elasticsearch
            $models = [$this->getIndex($id)];
        }

        return $this->mappingModel(array_shift($models), $userId);
    }

    /**
     * @param array $item
     * @param int $userId
     * @return array
     */
    private function mappingModel(array $item, ?int $userId): array
    {
        $item['message_no_read'] = null;
        $item['user'] = null;

        if ($userId) {
            // Не прочитанные сообщения
            $item['message_no_read'] = $this->getNotReadMessages($item['id'], $userId);

            $item['users'] = array_map(function ($user) {
                $user['tutor'] = TutorProfile::select(['id'])
                    ->where('user_id', $user['user_id'])
                    ->where('status', TutorProfile::STATUS_ACTIVE)
                    ->first();

                if ($user['tutor']) {
                    $user['tutor'] = $user['tutor']->toArray();
                }

                return $user;
            }, $item['users']);

            foreach ($item['users'] as $user) {
                if ((int) $user['user_id'] !== (int) $userId) {
                    $item['user'] = $user;
                    break;
                }
            }
        }

        $item['max_updated_at'] = null;

        $lastMessage = Messages::where('dialog_id', $item['id'])
            ->orderByDesc('created_at')
            ->first();

        if (!empty($lastMessage)) {
            $item['max_updated_at'] = (string) $lastMessage->created_at;
        }

        return $item;
    }

    /**
     * Не прочитанные сообщения
     *
     * @param int $dialogId
     * @param int $userId
     * @return int
     */
    public function getNotReadMessages(int $dialogId, int $userId): int
    {
        $dateVisited = DialogUsers::select('visited_at')
            ->where('dialog_id', $dialogId)
            ->where('user_id', $userId)
            ->whereNotNUll('visited_at')
            ->first();

        if (empty($dateVisited)) {
            return 0;
        }

        return Messages::where('dialog_id', $dialogId)
            ->where('user_id', '!=', $userId)
            ->where('created_at', '>', $dateVisited->visited_at)
            ->count();
    }
}