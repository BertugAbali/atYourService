<?php

namespace Database\Factories;

use App\Models\Service_Areas;
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
        $service_areas =  Service_Areas::all();

        $areas_names=array();

        foreach ($service_areas as $area) {
            $areas_names[]=$area->name;
        }

        return [
            'title' => fake()->sentence(),
            'detail' => fake()->text(1000),
            'area' => $areas_names[array_rand($areas_names)],
            'price' => fake()->numberBetween(10,1000),   
            'owner_id' => fake()->numberBetween(1,50),
            'path' => 'empty-service.png',
        ];
    }
}
