<?php

namespace App\Console\Commands\Socket;

use Ratchet\App;
use App\Console\Commands\Socket\Servers\OnlineUserServer;
use Illuminate\Console\Command;

class Connect extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'socket:listen';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * @var integer
     */
    protected $port = 8888;

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$this->isWorker()) {
            $this->runApp();
        }
    }

    /**
     * @return bool
     */
    private function isWorker()
    {
        $host= gethostname();
        $ip = gethostbyname($host);

        $fp = @fsockopen($ip, $this->port);

        return $fp ? true : false;
    }

    /**
     * Start worker
     */
    private function runApp()
    {
        $url = parse_url(env('APP_URL'));

        $app = new App($url['host'], $this->port);

        foreach ($this->routeServers() as $route => $server) {
            $app->route('/' . ltrim($route, '/'), new $server);
        }

        $app->run();
    }

    /**
     * @return array
     */
    private function routeServers(): array
    {
        return [
            'online' => OnlineUserServer::class
        ];
    }
}
