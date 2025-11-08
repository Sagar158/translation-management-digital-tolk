<?php

namespace Database\Factories;

use App\Models\Translation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Translation>
 */
class TranslationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Translation::class;
    public function definition(): array
    {
        return [
                    'locale' => $this->faker->randomElement(['en', 'fr', 'es']),
                    'key' => $this->faker->word(),
                    'content' => $this->faker->sentence(),
                    'tag' => $this->faker->randomElement(['mobile', 'desktop', 'web']),
                ];
    }
}
