<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::create([
            'name' => 'admin2',
            'email' => 'admin2@smiomio.com',
            'password' => password_hash('Pa$$word!', PASSWORD_DEFAULT),
        ]);
    }
}
