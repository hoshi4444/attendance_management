<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Work>
 */
class WorkFactory extends Factory
{
    // 今日から -count日 までのデータ
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
            'user_id' => $this->faker->numberBetween(1, 10),
            'work_type_id' => $this->faker->numberBetween(1, 5),
            'on_date' => date('Y-m-d', strtotime(' - '.self::$num.' day')),
        ];
    }
}
