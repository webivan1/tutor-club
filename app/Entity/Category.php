<?php

namespace App\Entity;

use App\Services\ElasticSearch\ModelSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method static Builder childAll
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
