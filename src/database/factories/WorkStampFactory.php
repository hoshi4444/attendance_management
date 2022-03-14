<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkStamp>
 */
class WorkStampFactory extends Factory
{
    // 現在時刻から1時間ずつ加算したデータ
    static $num = 0;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        self::$num++;
        return [
            'work_id' => $this->faker->numberBetween(1, 100),
            'stamp_at' => date('H:i:s', strtotime(' + '.self::$num.' hour')),
        ];
    }
}
