<?php

namespace Database\Factories;

use App\Models\Url;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Click>
 */
class ClickFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

        $countries = [
            "Belgium" => "BE",
            "Canada" => "CA",
            "France" => "FR",
            "Germany" => "DE",
            "Spain" => "ES"
        ];

        $country = fake()->randomElement(array_keys($countries));

        return [
            'url_id' => Url::all()->random()->id,
            'ip_address' => fake()->ipv4(),
            'browser' => fake()->randomElement(['firefox', 'chrome', 'safari']),
            'device' => fake()->randomElement(['mobile', 'desktop', 'tablet']),
            'country' => $country,
            'country_code' => $countries[$country],
            'created_at' => fake()->dateTimeBetween('-20 days', now()),
            'lat' => fake()->latitude(-90, 90),
            'lon' => fake()->longitude(-180, 180),
        ];
    }
}
