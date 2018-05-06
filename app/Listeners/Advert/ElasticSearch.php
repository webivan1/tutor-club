<?php

namespace App\Listeners\Advert;

use App\Entity\Advert\Advert;
use App\Events\Advert\ChangeAdvert;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class ElasticSearch implements ShouldQueue
{
    use InteractsWithQueue;

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
     * @param  ChangeAdvert  $event
     * @return void
     */
    public function handle(ChangeAdvert $event)
    {
        if (method_exists($this, $event->getEventName())) {
            call_user_func([$this, $event->getEventName()], $event->getAdvert());
        }
    }

    /**
     * Handle update advert
     *
     * @param Advert $advert
     * @return void
     */
    public function update(Advert $advert): void
    {
        if ($advert->isActive()) {
            $this->service->update($advert);
        } else {
            $this->service->delete($advert);
        }
    }

    /**
     * Handle create advert
     *
     * @param Advert $advert
     */
    public function create(Advert $advert): void
    {
        if ($advert->isActive()) {
            $this->service->add($advert);
        }
    }

    /**
     * Handle delete advert
     *
     * @param Advert $advert
     */
    public function delete(Advert $advert): void
    {
        $this->service->delete($advert);
    }
}
