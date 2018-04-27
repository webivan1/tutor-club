<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;
use App\Entity\Admin\Role;

class Roles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Default roles
     *
     * @return array
     */
    public function rolesConfig()
    {
        return [
            [
                'name' => 'super_admin',
                'title' => 'Super admin',
                'level' => 1,
            ],
            [
                'name' => 'admin',
                'title' => 'Admin',
                'level' => 2,
            ],
            [
                'name' => 'moderator',
                'title' => 'Moderator',
                'level' => 3,
            ],
            [
                'name' => 'content',
                'title' => 'Content',
                'level' => 4,
            ],
            [
                'name' => 'client',
                'title' => 'Client',
                'level' => 5,
            ],
            [
                'name' => 'user',
                'title' => 'User',
                'level' => 6,
            ],
        ];
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $role = Role::first();

        if (empty($role)) {
            \DB::beginTransaction();

            try {
                foreach ($this->rolesConfig() as $role) {
                    Role::insert($role);
                    \DB::commit();
                }
            } catch (\Exception $e) {
                \DB::rollBack();
            }
        } else {
            $this->error('Roles is not empty!');
        }
    }
}
