<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class ServiceFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $array = array("Photographer", "Programmer", "Designer", "Writer", "Content Creator","Artist");
        return [
            'title' => fake()->sentence(),
            'detail' => fake()->text(1000),
            'area' => $array[array_rand($array)],
            'price' => fake()->numberBetween(10,1000),   
            'owner_id' => fake()->numberBetween(1,50),
            'path' => 'ofNe8xphAelNN8vWJ6YbBNnHT5U4ED71F19bO754.jpg',
        ];
    }
}
