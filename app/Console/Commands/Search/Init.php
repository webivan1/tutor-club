<?php

namespace App\Console\Commands\Search;

use App\Entity\Advert\Advert;
use App\Entity\Category;
use App\Entity\Chat\Dialogs;
use App\Services\ElasticSearch\ElasticSearchService;
use Illuminate\Console\Command;

class Init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'search:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create indexes';

    /**
     * @var ElasticSearchService
     */
    private $service;

    /**
     * Init constructor.
     * @param ElasticSearchService $service
     */
    public function __construct(ElasticSearchService $service)
    {
        parent::__construct();

        $this->service = $service;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->service->createIndex(new Advert());
        $this->service->createIndex(new Category());
        $this->service->createIndex(new Dialogs());
    }
}
