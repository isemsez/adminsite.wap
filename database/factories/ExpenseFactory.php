<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'details'=>$this->faker->text(30),
            'amount'=>$this->faker->numberBetween(100,10000),
            'expense_date'=>$this->faker->date,
        ];
    }
}
