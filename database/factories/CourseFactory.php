<?php

namespace Database\Factories;

use App\Models\Course;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Course>
 */
class CourseFactory extends Factory
{
    protected $model = Course::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'paddle_product_id' => fake()->uuid,
            'slug' => fake()->slug(),
            'tagline' => fake()->sentence(),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'image_name' => 'image.png',
            'learnings' => ['Learn A', 'Learn B', 'Learn C']
        ];
    }

    public function released(Carbon $date = null): Factory
    {
        return $this->state(fn ($attributes) => ['released_at' => $date ?? now()]);
    }
}
