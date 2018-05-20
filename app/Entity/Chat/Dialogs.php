<?php

namespace App\Entity\Chat;

use App\Entity\User;
use App\Services\ElasticSearch\ElasticSearchModel;
use App\Services\ElasticSearch\ElasticSearchService;
use App\Services\ElasticSearch\ModelSearch;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;

/**
 * @property integer $id
 * @property string $title
 * @property string $status
 * @property integer $admin_id
 */
class Dialogs extends Model implements ModelSearch
{
    /**
     * @var string
     */
    public $table = 'dialogs';

    /**
     * @var array
     */
    public $fillable = ['title', 'status', 'admin_id'];

    const STATUS_OPEN = 'open';
    const STATUS_CLOSE = 'close';

    /**
     * @return array
     */
    public function statuses()
    {
        return [
            self::STATUS_CLOSE => t('Close'),
            self::STATUS_OPEN => t('Open')
        ];
    }

    /**
     * Create new dialog
     *
     * @param string $title
     * @param int $adminId
     * @return Dialogs
     */
    public static function new(string $title, int $adminId): Dialogs
    {
        $model = new self();
        $model->title = $title;
        $model->admin_id = $adminId;
        $model->status = self::STATUS_OPEN;
        $model->save();
        return $model;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messages()
    {
        return $this->hasMany(Messages::class, 'dialog_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function users()
    {
        return $this->hasMany(DialogUsers::class, 'dialog_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function user()
    {
        return $this->hasOne(DialogUsers::class, 'dialog_id', 'id');
    }

    /**
     * @return bool
     */
    public function isClose(): bool
    {
        return self::STATUS_CLOSE === $this->status;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return self::STATUS_OPEN === $this->status;
    }

    /**
     * Init elasticsearch model
     *
     * @return ElasticSearchModel
     */
    public static function initElasticModel(): ElasticSearchModel
    {
        /** @var ElasticSearchService $service */
        $service = app()->make(ElasticSearchService::class);

        /** @var ElasticSearchModel $model */
        $model = $service->find(new self);

        return $model;
    }

    /**
     * Имеет ли доступ юзер к данному диалогу
     *
     * @param int $dialogId
     * @param int $userId
     * @return bool|array
     */
    public function isAccess(int $dialogId, int $userId)
    {
        $model = self::initElasticModel();

        $model->setCustomQuery([
            'bool' => [
                'must' => [
                    ['term' => ['id' => $dialogId]],
                    ['term' => ['user_ids' => $userId]]
                ]
            ]
        ]);

        return $model->queryTotal() >= 1
            ? $model->first()
            : false;
    }

    /**
     * Может ли юзер отправлять сообщения в данный диалог
     *
     * @param int $dialogId
     * @param int $userId
     * @return bool|array
     */
    public function isSendMessage(int $dialogId, int $userId)
    {
        if (!$source = $this->isAccess($dialogId, $userId)) {
            return false;
        }

        if (!isset($source['status']) || $source['status'] === self::STATUS_CLOSE) {
            throw new \DomainException(t('Dialog is closed'));
        }

        return $source;
    }

    /**
     * Join к юзерам диалога
     *
     * @param Builder $builder
     * @param int $userId
     * @param string $tableName
     * @return Builder
     */
    public function scopeSearchUser(Builder $builder, int $userId, string $tableName = 'dialog_users'): Builder
    {
        return $builder->join($tableName, function (JoinClause $join) use ($userId, $tableName) {
            $tableSegments = explode(' ', $tableName);
            $table = array_pop($tableSegments);
            $join->on("dialogs.id", "$table.dialog_id");
            $join->where("$table.user_id", $userId);
        });
    }

    /**
     * Поиск общего диалога у всех пользователей
     *
     * @param string|array $users
     * @return int|null
     */
    public function existDialog($users): ?int
    {
        $users = !is_array($users) ? func_get_args() : $users;

        $model = self::initElasticModel();

        $model->setCustomQuery([
            'bool' => [
                'must' => array_map(function ($user) {
                    return ['term' => ['user_ids' => $user]];
                }, $users)
            ]
        ]);

        return $model->queryTotal() > 0
            ? (int) $model->first()['id']
            : null;
    }

    /**
     * @return array
     */
    public function settings(): array
    {
        return [
            'analysis' => [
                'char_filter' => [
                    'replace' => [
                        'type' => 'mapping',
                        'mappings' => [
                            '&=> and '
                        ],
                    ],
                ],
                'filter' => [
                    'word_delimiter' => [
                        'type' => 'word_delimiter',
                        'split_on_numerics' => false,
                        'split_on_case_change' => true,
                        'generate_word_parts' => true,
                        'generate_number_parts' => true,
                        'catenate_all' => true,
                        'preserve_original' => true,
                        'catenate_numbers' => true,
                    ],
                    'trigrams' => [
                        'type' => 'ngram',
                        'min_gram' => 3,
                        'max_gram' => 6,
                    ],
                ],
                'analyzer' => [
                    'default' => [
                        'type' => 'custom',
                        'char_filter' => [
                            'html_strip',
                            'replace',
                        ],
                        'tokenizer' => 'whitespace',
                        'filter' => [
                            'lowercase',
                            'word_delimiter',
                            'trigrams',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function mappingProperties(): array
    {
        return [
            'status' => [
                'type' => 'keyword'
            ],
            'title' => [
                'type' => 'text'
            ],
            'user_ids' => [
                'type' => 'integer'
            ],
            'updated_at' => [
                'type' => 'date'
            ],
            'users' => [
                'type' => 'nested',
                'properties' => [
                    'id' => [
                        'type' => 'integer',
                    ],
                    'dialog_id' => [
                        'type' => 'integer'
                    ],
                    'user_id' => [
                        'type' => 'integer'
                    ],
                    'user' => [
                        'type' => 'nested',
                        'properties' => [
                            'id' => [
                                'type' => 'integer'
                            ],
                            'name' => [
                                'type' => 'text'
                            ]
                        ]
                    ]
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    public function getIndexName(): string
    {
        return $this->getTable();
    }

    /**
     * @return string
     */
    public function getSourceName(): string
    {
        return 'dialogs';
    }

    /**
     * @return \Generator|null
     */
    public function getAllIndexes(): ?\Generator
    {
        foreach (self::select(['id'])->cursor() as $item) {
            yield $this->getIndex($item->id);
        }

        return null;
    }

    /**
     * @param int $id
     * @return array
     */
    public function getIndex(int $id): array
    {
        $dialog = self::select(['dialogs.*', new Expression('MAX(m.created_at) AS last_message_at')])
            ->where('dialogs.id', $id)
            ->join('messages as m', 'm.dialog_id', 'dialogs.id')
            ->with([
                'users' => function (HasMany $builder) {
                    $builder->with(['user' => function ($builder) {
                        $builder->select(['id', 'name']);
                    }]);
                }
            ])
            ->withCount('messages')
            ->groupBy('dialogs.id')
            ->first()
            ->toArray();

        $dialog['user_ids'] = array_column($dialog['users'], 'user_id');
        $dialog['updated_at'] = (string) now()->setTimestamp(strtotime($dialog['last_message_at']))
            ->format(DATE_ATOM);

        return $dialog;
    }
}
