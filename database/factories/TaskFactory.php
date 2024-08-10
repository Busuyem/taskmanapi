<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;
use Faker\Factory as FakerFactory;

class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    protected $model = Task::class;


    public function definition()
    {
        $faker = FakerFactory::create(); // Use Faker directly

        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'completed_at' => now(),
        ];
    }
}
