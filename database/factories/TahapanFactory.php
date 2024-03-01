<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tahapan>
 */
class TahapanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'code' => $this->faker->unique()->numberBetween(1, 100000),
            'nama' => $this->faker->word(),
            'tahun' => $this->faker->year(),
            'keterangan' => $this->faker->text(),
        ];
    }
}
