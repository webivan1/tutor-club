<?php

namespace App\Console\Commands\Socket;

use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
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
        foreach ($this->routeServers() as $port => $server) {
            $server = IoServer::factory(
                new HttpServer(
                    new WsServer(
                        new $server
                    )
                ),
                $port
            );

            $server->run();
        }
    }

    /**
     * @return array
     */
    private function routeServers(): array
    {
        return [
            8888 => OnlineUserServer::class
        ];
    }
}
