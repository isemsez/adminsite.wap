<?php

namespace Database\Factories;

use App\Http\Controllers\Controller;
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
        $photo_path = "backend/pics/product";
        Controller::create_dir_if_necessary( public_path( $photo_path ) );

        Controller::print_to_console('.');

        return [
            'product_name' => $this->faker->word,
            'product_code' => $this->faker->numberBetween( 1001, 9999 ),
            'category_id'  => $this->faker->numberBetween(1,10),
            'supplier_id'  => $this->faker->numberBetween(1,10),
            'root'  => $this->faker->word,
            'buying_price'  => $this->faker->numberBetween(100,1000),
            'selling_price'  => $this->faker->numberBetween(100,1000),
            'buying_date'  => $this->faker->date,
            'product_quantity'  => $this->faker->numberBetween(1,10),
            'photo'  => ( "/$photo_path/" ) . $this->faker->image( public_path( $photo_path ), 320, 240, null, false ),
        ];
    }
}

