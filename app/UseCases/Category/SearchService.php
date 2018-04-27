<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 27.04.2018
 * Time: 10:00
 */

namespace App\UseCases\Category;


use App\Entity\Category;
use App\Services\ElasticSearch\ElasticSearchService;

class SearchService
{
    /**
     * @var ElasticSearchService
     */
    private $service;

    /**
     * SearchService constructor.
     * @param ElasticSearchService $service
     */
    public function __construct(ElasticSearchService $service)
    {
        $this->service = $service;
    }

    /**
     * Search by text
     *
     * @param string $text
     * @param int $limit
     * @return array
     * @throws \DomainException
     */
    public function search(string $text, int $limit = 30): array
    {
        $sources = $this->service->find(new Category())
            ->setCustomQuery([
                'bool' => [
                    'must' => [
                        [
                            'bool' => [
                                'minimum_should_match' => 1,
                                'should' => [
                                    [
                                        'query_string' => [
                                            'default_operator' => 'AND',
                                            'fields' => array_map(function ($key) {
                                                return 'name_' . $key;
                                            }, \LaravelLocalization::getSupportedLanguagesKeys()),
                                            'query' => $text . "*"
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ])
            ->setPagination($limit, 1)
            ->querySource();

        if (empty($sources)) {
            throw new \DomainException(t('home.notFoundCategorySearch'));
        }

        return $this->correctResponseArray($sources);
    }

    /**
     * @param array $sources
     * @return array
     */
    private function correctResponseArray(array $sources): array
    {
        return array_map(function ($item) {
            $item = array_combine(
                ['slug', 'name'],

                array_intersect_key($item, array_flip([
                    'slug', 'name_' . app()->getLocale()
                ]))
            );

            // full url to category
            $item['slug'] = route('category.show', $item['slug']);

            return $item;
        }, $sources);
    }
}