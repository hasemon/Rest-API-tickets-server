<?php

namespace Database\Seeders;

use App\Models\Ticket;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Database\Factories\TicketFactory;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(10)->create();

        User::create([
            'email' => 'manager@mail.com',
            'password' => bcrypt('password'),
            'name' => 'The Manager',
            'is_manager' => true
        ]);

        Ticket::factory(100)
            ->recycle($users)
            ->create();
    }
}
