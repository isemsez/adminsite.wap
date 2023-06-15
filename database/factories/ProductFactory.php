<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'product_name' => $this->faker->word,
            'product_code' => $this->faker->numberBetween( 1001, 9999 ),
            'category_id'  => $this->faker->numberBetween(1,9),
            'supplier_id'  => $this->faker->numberBetween(1,10),
            'root'  => $this->faker->word,
            'buying_price'  => $this->faker->numberBetween(100,1000),
            'selling_price'  => $this->faker->numberBetween(100,1000),
            'buying_date'  => $this->faker->date,
            'product_quantity'  => $this->faker->numberBetween(1,10),
            'image'  => $this->faker->imageUrl,
        ];
    }
}

