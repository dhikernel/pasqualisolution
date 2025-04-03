<?php

namespace Database\Factories\Domain\TravelRequest\Models;

use App\Domain\TravelRequest\Models\TravelRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\TravelRequest\Models\TravelRequest>
 */
class TravelRequestFactory extends Factory
{
    protected $model = TravelRequest::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return User::factory()->create()->id;
            },
            'applicant_name' => $this->faker->name(),
            'destination' => $this->faker->city(),
            'departure_date' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'return_date' => $this->faker->dateTimeBetween('+1 day', '+1 year')->format('Y-m-d'),
            'status' => $this->faker->randomElement(['solicitado', 'aprovado', 'cancelado']),
        ];
    }
}
