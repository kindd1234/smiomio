<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AddAdmin extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\User::create([
            'name' => 'Admin 2',
            'email' => 'admin2@smiomio.com',
            'password' => password_hash('Pa$$word!', PASSWORD_DEFAULT),
        ]);
    }
}
