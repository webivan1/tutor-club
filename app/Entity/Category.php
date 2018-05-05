<?php

namespace App\Entity;

use App\Entity\Advert\Advert;
use App\Entity\Advert\AdvertPrice;
use App\Services\ElasticSearch\ModelSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Query\JoinClause;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method static childAll()
 * @method joinChild()
 * @method joinAdvertItems()
 */
class Category extends Model implements ModelSearch
{
    use NodeTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'title',
        'description',
        'content',
        'parent_id',
        '_lft',
        '_rgt'
    ];

    /**
     * @var string
     */
    public $table = 'category';

    /**
     * @param Builder $query
     * @param Category $category
     * @return Builder
     */
    public function scopeChildAll(Builder $query, Category $category)
    {
        return $query->where('_lft', '>', $category->getLft())
            ->where('_rgt', '<', $category->getRgt())
            ->withDepth();
    }

    /**
     * @param Category $category
     * @return Collection
     * @throws \Exception
     */
    public static function childToTree(Category $category): Collection
    {
        $child = self::childAll($category)->get()->toTree();

        if ($child->count() === 0) {
            throw new \Exception('Empty category ' . $category->name);
        }

        return $child;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attributes()
    {
        return $this->hasMany(Attribute::class, 'category_id', 'id');
    }

    /**
     * @param \Closure $withQuery
     * @return array
     */
    public function parentAttributes(?\Closure $withQuery = null): array
    {
        return $this->parent ? $this->parent->allAttributes($withQuery) : [];
    }

    /**
     * @param \Closure $withQuery
     * @return array
     */
    public function allAttributes(?\Closure $withQuery = null): array
    {
        return array_merge(
            $this->parentAttributes($withQuery),
            is_callable($withQuery)
                ? call_user_func($withQuery, $this->attributes()->orderByDesc('sort'))
                : $this->attributes()->orderByDesc('sort')->getModels()
        );
    }

    /**
     * @return array
     */
    public function allAttributesCached(): array
    {
        return \Cache::tags([
            $this->getTable(),
            (new Advert)->getTable()
        ])->remember('all-attributes-by-' . $this->id, 360, function () {
            return array_map(function (Attribute $item) {
                if (!empty($item->variants)) {
                    $item->variants = $item->variantsToArray();
                }

                $item->label = t($item->label);

                return $item;
            }, $this->allAttributes());
        });
    }

    /**
     * List categories main
     *
     * @return array
     */
    public function listCategories()
    {
        $key = 'list.roots.categories.' . app()->getLocale();

        return \Cache::tags([$this->getTable()])->remember($key, 60 * 6, function () {
            return self::whereNull('parent_id')
                ->select(['category.*'])
                ->with(['children' => function (HasMany $builder) {
                    $builder
                        ->select(['category.*'])
                        ->joinCounts()
                        ->groupBy('category.id')
                        ->orderByDesc('counts.total_adverts');
                }])
                ->joinCounts()
                ->orderByDesc('counts.total_adverts')
                ->groupBy('category.id')
                ->get()
                ->toArray();
        });
    }

    /**
     * Left join counts categories
     *
     * @param Builder $builder
     * @return Builder|void
     */
    public function scopeJoinCounts(Builder $builder)
    {
        $builder->leftJoin((new CategoryCounts)->getTable() . ' as counts', function (JoinClause $join) {
            $join->on('category.id', 'counts.category_id')
                ->where('counts.lang', app()->getLocale());
        });

        $builder->addSelect([
            new Expression('IF (counts.id IS NULL, 0, counts.total_categories) AS total_categories'),
            new Expression('IF (counts.id IS NULL, 0, counts.total_adverts) AS total_adverts')
        ]);

        $builder->groupBy(['counts.id']);
    }

    /**
     * Left join child categories
     *
     * @param Builder $builder
     * @return Builder|void
     */
    public function scopeJoinChild(Builder $builder)
    {
        $builder->leftJoin($this->getTable() . ' AS child', function (JoinClause $join) {
            $join->on('child._lft', '>=', 'category._lft')
                ->on('child._rgt', '<=', 'category._rgt')
                ->whereNotNull('child.parent_id');
        });

        $builder->groupBy(['category.id'])
            ->addSelect(new Expression('COUNT(DISTINCT(child.id)) AS total_categories'));
    }

    /**
     * Left join all active adverts
     *
     * @param Builder $builder
     * @param string $lang
     * @return Builder|void
     */
    public function scopeJoinAdvertItems(Builder $builder, string $lang = null)
    {
        $builder->leftJoin((new AdvertPrice())->getTable() . ' AS ap', function (JoinClause $join) {
            $join->on('child.id', 'ap.category_id');
        });

        $builder->leftJoin((new Advert())->getTable() . ' AS a', function (JoinClause $join) use ($lang) {
            $join->on('a.id', 'ap.advert_id')
                ->where('a.status', Advert::STATUS_ACTIVE)
                ->where('a.lang', !$lang ? app()->getLocale() : $lang);
        });

        $builder->addSelect(new Expression('COUNT(DISTINCT(a.id)) AS total_adverts'));
    }

    /**
     * Get child categories by parent
     *
     * @return array
     */
    public function childCategories(): array
    {
        return $this->children()
            ->select(['category.id', 'category.slug', 'category.name'])
            ->joinCounts()
            ->orderByDesc('total_adverts')
            ->groupBy('category.id')
            ->get()
            ->toArray();
    }

    /**
     * {@inheritdoc}
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
                        'min_gram' => 2,
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
                            //'trigrams',
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function mappingProperties(): array
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getIndexName(): string
    {
        return $this->getTable();
    }

    /**
     * {@inheritdoc}
     */
    public function getSourceName(): string
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function getAllIndexes(): ?\Generator
    {
        foreach (self::select(['id'])->cursor() as $item) {
            yield $this->getIndex($item->id);
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getIndex(int $id): array
    {
        $category = self::where('id', $id)->first();

        $result = [
            'id' => $category->id,
            'slug' => $category->slug,
        ];

        foreach (\LaravelLocalization::getSupportedLanguagesKeys() as $key) {
            $result['name_' . $key] = t($category->name, [], $key);
            $result['title_' . $key] = t($category->title, [], $key);
        }

        return $result;
    }
}
