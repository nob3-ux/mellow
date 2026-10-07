<?php

namespace Database\Factories;

use App\Enums\Slot;
use App\Models\Availability;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Availability>
 */
class AvailabilityFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'date' => today()->addDays(fake()->numberBetween(1, 60))->toDateString(),
            'slot' => fake()->randomElement(Slot::cases()),
        ];
    }
}
