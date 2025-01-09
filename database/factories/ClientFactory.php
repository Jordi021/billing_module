<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            "id" => $this->faker->numerify("##########"), // Genera un UUID único
            "name" => $this->faker->name(),
            "last_name" => $this->faker->lastName(),
            "birth_date" => $this->faker->date("Y-m-d", "2000-01-01"),
            "client_type" => $this->faker->randomElement([
                "Cash",
                "Credit",
            ]),
            "address" => $this->faker->address,
            "phone" => $this->faker->numerify("##########"),
            "email" => $this->faker->unique()->safeEmail,
            "status" => $this->faker->randomElement([true, false]),
        ];
    }
}
