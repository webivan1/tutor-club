<?php

namespace App\Listeners\Category;

use App\Entity\Category;
use App\Events\Category\ChangeCategory;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ElasticSearch implements ShouldQueue
{
    use InteractsWithQueue;

    private $service;

    /**
     * Create the event listener.
     *
     * @param ElasticSearchService $service
     */
    public function __construct(ElasticSearchService $service)
    {
        $this->service = $service;
    }

    /**
     * Handle the event.
     *
     * @param  ChangeCategory  $event
     * @return void
     */
    public function handle(ChangeCategory $event)
    {
        if (method_exists($this, $event->getEventName())) {
            call_user_func([$this, $event->getEventName()], $event->getCategory());
        }
    }

    /**
     * Handle update category
     *
     * @param Category $category
     * @return void
     */
    public function update(Category $category): void
    {
        $this->service->update($category);
    }

    /**
     * Handle create category
     *
     * @param Category $category
     * @return void
     */
    public function create(Category $category): void
    {
        $this->service->add($category);
    }

    /**
     * Handle delete category
     *
     * @param Category $category
     * @return void
     */
    public function delete(Category $category): void
    {
        $this->service->delete($category);
    }
}
