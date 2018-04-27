<?php

namespace App\Console\Commands\User;

use App\Entity\Admin\Role;
use App\Entity\Admin\RoleHasUser;
use Illuminate\Console\Command;
use App\Entity\User;

class Assign extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:assign {email} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign role';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (!$user = User::where('email', $this->argument('email'))->first()) {
            $this->error('Undefined user!');
            return;
        }

        // Delete all roles by user
        RoleHasUser::where('entity_id', $user->id)
            ->where('entity_type', (new User)->getMorphClass())
            ->delete();

        if (!$role = Role::where('name', $this->argument('role'))->first()) {
            $this->error('Undefined role!');
            return;
        }

        $user->assign($role);
    }
}
