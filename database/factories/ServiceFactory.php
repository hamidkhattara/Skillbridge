<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Generate a random 3-word title (e.g., "Advanced Math Coaching")
            'name' => fake()->words(3, true),
            
            // Generate a random paragraph of text
            'description' => fake()->paragraph(),
            
            // Pick a random realistic duration in minutes
            'default_duration' => fake()->randomElement([30, 45, 60, 90, 120]),
            
            // Random price between 10.00 and 150.00 (with 2 decimals)
            'price' => fake()->randomFloat(2, 10, 150),
            
            // Randomly pick one of our allowed ENUM values
            'billing_unit' => fake()->randomElement(['session', 'hour']),
            'modality' => fake()->randomElement(['online', 'in_person', 'either']),
            
            // Generate a valid hex color code (e.g., #f03a11)
            'color' => fake()->hexColor(),
            
            // 80% chance to be true, 20% chance to be false
            // The exercise asks for "some inactive" services
            'is_active' => fake()->boolean(80),
            
            // 20% chance to be true
            'is_recurring_default' => fake()->boolean(20),
        ];
    }
}