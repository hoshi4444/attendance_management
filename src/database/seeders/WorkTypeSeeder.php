<?php

namespace Database\Seeders;

use App\Models\WorkType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WorkTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $work_types = [
            ["name" => "かなり忙しい"],
            ["name" => "忙しい"],
            ["name" => "普通"],
            ["name" => "暇"],
            ["name" => "かなり暇"],
        ];

        WorkType::upsert($work_types, 'id');
    }
}
