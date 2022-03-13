<?php

namespace Database\Seeders;

use App\Models\WorkStamp;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkStampSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WorkStamp::factory()
            ->count(1000)
            ->create();
    }
}
