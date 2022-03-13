<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            WorkTypeSeeder::class,
            CurrentUserSeeder::class,
            UserSeeder::class,
            WorkSeeder::class,
            WorkStampSeeder::class,
        ]);
    }
}
