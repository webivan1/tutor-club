<?php
/**
 * Created by PhpStorm.
 * User: Ivan
 * Date: 26.04.2018
 * Time: 23:15
 */

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Category\SearchRequest;
use App\UseCases\Category\SearchService;

class SearchController extends Controller
{
    /**
     * @var SearchService
     */
    private $service;

    /**
     * SearchController constructor.
     * @param SearchService $service
     */
    public function __construct(SearchService $service)
    {
        $this->service = $service;
    }

    /**
     * Search category
     *
     * @param SearchRequest $request
     * @return array
     */
    public function index(SearchRequest $request)
    {
        try {
            $result = $this->service->search($request->input('search'));
        } catch (\DomainException $e) {
            return response(['message' => $e->getMessage()], 400);
        }

        return response($result);
    }
}