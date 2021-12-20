<?php

namespace Database\Factories;

use App\Models\PromoCode;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class PromoCodeFactory extends Factory
{
    protected $model = PromoCode::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $start_date = $this->faker->dateTimeBetween('+0 days', '+1 month');
        $start_date_clone = clone $start_date;

        $end_date = $this->faker->dateTimeBetween($start_date, $start_date_clone->modify('+1 month'));
        return [
            'code' => Str::upper(Str::random(12)),
            'start_date' => $start_date,
            'end_date'   => $end_date,
            'amount'     => $this->faker->randomNumber(),
            'quota'      => $this->faker->randomNumber()
        ];
    }
}
