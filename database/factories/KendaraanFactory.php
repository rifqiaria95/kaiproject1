<?php

namespace Database\Factories;

use App\Models\Kendaraan;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kendaraan>
 */
class KendaraanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Kendaraan::class;

    public function definition()
    {
        return [
            'merk_kendaraan'  => $this->faker->company,
            'tahun_pembuatan' => $this->faker->year,
            'nomor_rangka'    => Str::random(17),
            'nomor_mesin'     => Str::random(10),
            'cc'              => $this->faker->numberBetween(100, 2000),
            'produsen'        => $this->faker->company,
            'permission'      => $this->faker->optional()->word,
            'created_at'      => now(),
            'updated_at'      => now(),
        ];
    }
}
