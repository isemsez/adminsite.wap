<?php

namespace Database\Factories;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $photo_path = "backend/pics/supplier";
        Controller::create_dir_if_necessary( public_path( $photo_path ) );

        Controller::print_to_console('.');

        return [
            'name' => $this->faker->name,
            'email' => $this->faker->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'address' => preg_replace( '/\n/', ', ', $this->faker->address ),
            'shopname' => $this->faker->word,
            'photo' => ( "/$photo_path/" ) . $this->faker->image( public_path( $photo_path ), 320, 240, null, false )
        ];
    }
}


