<?php

namespace App\Console\Commands\User;

use App\UseCases\Auth\RegisterService;
use Illuminate\Console\Command;
use App\Entity\User;

class Verify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verify user';

    /**
     * @var RegisterService
     */
    private $service;

    /**
     * Verify constructor.
     * @param RegisterService $service
     */
    public function __construct(RegisterService $service)
    {
        $this->service = $service;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$user = User::where('email', $this->argument('email'))->first()) {
            $this->error('Undefined user email: ' . $this->argument('email'));
            return false;
        }

        $this->service->verify($user);

        $this->info('OK!');

        return true;
    }
}
