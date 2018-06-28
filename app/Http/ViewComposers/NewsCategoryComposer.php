<?php

namespace App\Http\ViewComposers;

use App\Entity\Media\Category;
use Illuminate\View\View;


class NewsCategoryComposer
{
    protected $categoryList;

    /**
     * Create a new composer.
     *
     * @param  Category  $category
     * @return void
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
        $view->with('data', $this->categoryList->where('status', $this->categoryList::STATUS_ACTIVE)->get());
    }
}