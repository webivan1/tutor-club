<?php

namespace App\Listeners\Chat;

use App\Entity\Chat\Dialogs;
use App\Events\Chat\ChangeDialog;
use App\Events\Chat\CreateDialog;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ElasticSearch
{
    /**
     * @var ElasticSearchService
     */
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
     * @param  ChangeDialog  $event
     * @return void
     */
    public function handle(ChangeDialog $event)
    {
        if (method_exists($this, $event->getEventName())) {
            call_user_func([$this, $event->getEventName()], $event->getDialog());
        }
    }

    /**
     * Handle update category
     *
     * @param Dialogs $category
     * @return void
     */
    public function update(Dialogs $category): void
    {
        $this->service->update($category);
    }

    /**
     * Handle create category
     *
     * @param Dialogs $category
     * @return void
     */
    public function create(Dialogs $category): void
    {
        $this->service->add($category);
    }

    /**
     * Handle delete category
     *
     * @param Dialogs $category
     * @return void
     */
    public function delete(Dialogs $category): void
    {
        $this->service->delete($category);
    }
}
