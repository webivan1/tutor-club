<?php

use Illuminate\Database\Seeder;
use App\Entity\Chat\Messages;

class MessagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Messages::class, 100)->create();
    }
}
