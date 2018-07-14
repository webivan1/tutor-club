<?php
/**
 * Created by PhpStorm.
 * User: Zik
 * Date: 13.07.2018
 * Time: 10:42
 */

namespace App\Entity\Classroom;

use App\Components\Sort;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;

class ClassroomList extends Classroom
{
    /**
     * @var string
     */
    public $listStatus;

    /**
     * @param string $status
     * @return ClassroomList
     */
    public function setStatus(string $status): self
    {
        if (!array_key_exists($status, self::statuses())) {
            throw new \DomainException('Undefined status ' . $status);
        }

        $this->listStatus = $status;
        return $this;
    }

    /**
     * @param int $userId
     * @param int $pageSize
     * @return array
     */
    public function search(int $userId, int $pageSize = 10)
    {
        $query = self::from('classroom AS c')
            ->select(['c.*'])
            ->join('classroom_users AS cu', function (JoinClause $join) use ($userId) {
                $join->on('cu.classroom_id', 'c.id')
                    ->where('cu.user_id', $userId);
            });

        !$this->listStatus ?: $this->withStatus($query);

        $sort = $this->sortList($query, ['started_at' => SORT::SORT_ASC]);

        $query->groupBy(['c.id']);

        return [$query->paginate($pageSize), $sort];
    }

    /**
     * @param Builder $builder
     * @param array $defaultOrder
     * @return Sort
     */
    public function sortList(Builder $builder, array $defaultOrder = []): Sort
    {
        $sort = new Sort();
        $sort->setAttributes($this->sortAttributes());
        empty($defaultOrder) ?: $sort->setDefaultOrder($defaultOrder);
        $sort->init();

        $sort->orderWithQuery($builder);

        return $sort;
    }

    /**
     * @return array
     */
    public function sortAttributes(): array
    {
        return [
            'id' => [
                'asc' => ['c.id' => Sort::SORT_ASC],
                'desc' => ['c.id' => Sort::SORT_DESC],
                'label' => '#'
            ],
            'started_at' => [
                'asc' => ['c.started_at' => Sort::SORT_ASC],
                'desc' => ['c.started_at' => Sort::SORT_DESC],
                'label' => t('Date start lesson'),
            ]
        ];
    }

    /**
     * @param Builder $builder
     */
    public function withStatus(Builder $builder): void
    {
        if ($this->isSearchActive()) {
            $builder->where('c.status', self::STATUS_ACTIVE)
                // Юзер подтвердил приглашение
                ->where('cu.status', ClassroomUser::STATUS_ACTIVE);
        }

        if ($this->isSearchPending()) {
            $builder->where('c.status', self::STATUS_PENDING)
                ->where('c.started_at', '>=', new Expression('NOW()'))
                // Юзер пока не подтвердил приглашение
                ->where('cu.status', ClassroomUser::STATUS_DISABLED);
        }

        if ($this->isSearchDisabled()) {
            $builder->where('c.status', self::STATUS_CLOSED)
                ->orWhere(function ($query) {
                    $query->where('c.status', self::STATUS_PENDING)
                        ->where('c.started_at', '<', new Expression('NOW()'));
                });
        }

        if ($this->isSearchClosed()) {
            $builder->where('c.status', self::STATUS_CLOSED)
                ->where('cu.status', ClassroomUser::STATUS_ACTIVE);
        }
    }

    /**
     * @return bool
     */
    public function isSearchActive(): bool
    {
        return $this->listStatus === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isSearchPending(): bool
    {
        return $this->listStatus === self::STATUS_PENDING;
    }

    /**
     * @return bool
     */
    public function isSearchDisabled(): bool
    {
        return $this->listStatus === self::STATUS_CANCEL;
    }

    /**
     * @return bool
     */
    public function isSearchClosed(): bool
    {
        return $this->listStatus === self::STATUS_CLOSED;
    }
}