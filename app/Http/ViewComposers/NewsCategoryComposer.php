<?php

namespace App\Http\ViewComposers;

use App\Entity\Media\Category;
use Illuminate\View\View;


class NewsCategoryComposer
{
    /**
     * @var Category
     */
    private $categoryList;

    /**
     * Create a new composer.
     *
     * @param  Category  $category
     */
    public function __construct(Category $category)
    {
        $this->categoryList = $category;
    }

    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with('data', $this->categoryList->where('status', Category::STATUS_ACTIVE)->get());
    }
}