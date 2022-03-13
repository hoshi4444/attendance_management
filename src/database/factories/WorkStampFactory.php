<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkStamp>
 */
class WorkStampFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'work_id' => $this->faker->numberBetween(1, 100),
            'stamp_at' => $this->faker->dateTimeInInterval('- 12 hour', now()),
        ];
    }
}
