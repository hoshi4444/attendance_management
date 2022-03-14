<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkStamp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CurrentUserSeeder extends Seeder
{
    static $work_day = 0;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->withPersonalTeam()
            ->create([
                'name' => 'hoshi',
                'email' => 'h@h',
                'password' => Hash::make('aaaaaaaa'),
            ]);

        Work::factory()
            ->state([
                'user_id' => 1,
            ])
            ->count(30)
            ->has(
                WorkStamp::factory()
            )
            ->create();
    }
}
