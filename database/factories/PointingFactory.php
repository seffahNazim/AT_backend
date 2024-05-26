<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Pointing;
use App\Models\Employe;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pointing>
 */
class PointingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'employe_id' => Employe::inRandomOrder()->first()->id,
            'check_in' => fake()->optional()->time(),
            'check_out' => fake()->optional()->time(),
            'date' => fake()->optional()->dateTimeBetween('2024-01-01', '2024-12-31'),
            'statut' => fake()->optional()->randomElement(['present', 'absent', 'emergency', 'inProgress']),
        ];
    }
}
