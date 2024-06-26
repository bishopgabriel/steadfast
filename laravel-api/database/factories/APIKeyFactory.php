<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\APIKey>
 */
class APIKeyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'key' => bin2hex(random_bytes(16))  // alternatively import (use Illuminate\Support\Str;) and then use Str::random(32)
        ];
    }
}
