<?php

namespace Database\Seeders;

use App\Models\User;
use App\Services\User\Level;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Administrator',
            'username' => 'administrator',
            'level' => Level::Administrator,
        ]);

        User::factory()->create([
            'name' => 'Supervisor',
            'username' => 'supervisor',
            'level' => Level::Supervisor,
        ]);

        User::factory()->create([
            'name' => 'Worker',
            'username' => 'worker',
            'level' => Level::Worker,
        ]);
    }
}
