<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Url>
 */
class UrlFactory extends Factory
{



    private $urlsRandom = [
        'https://zod.dev',
        'https://fonts.google.com',
        'https://playcode.io',
        'https://laravel.com',
        'https://cssbattle.dev',
        'https://chatgpt.com',
        'https://www.youtube.com',
        'https://react-hot-toast.com',
        'https://www.npmjs.com/package/debounce',
        'https://www.behance.net',
        'https://scrollbar.app',
        'https://x.com',
        'https://codepen.io',
        'https://www.decathlon.ma',
    ];


    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::all()->random()->id,
            'original_url' => fake()->randomElement($this->urlsRandom),
            'code' => Str::random(6),
            'title' => fake()->words(rand(2, 3), true),
            'created_at'=> fake()->dateTimeBetween('-20 days', now())
        ];
    }
}
