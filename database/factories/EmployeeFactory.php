<?php

namespace Database\Factories;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Factories\Factory;
use ReflectionClass;

class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $photo_path = "backend/pics/employee";
        Controller::create_dir_if_necessary( public_path( $photo_path ) );

        Controller::print_to_console('.');

        return [
            'name'         => $this->faker->name,
            'email'        => $this->faker->safeEmail,
            'address'      => preg_replace( '/\n/', ', ', $this->faker->address ),
            'phone'        => $this->faker->phoneNumber,
            'salary'       => $this->faker->numberBetween( 1000, 6000 ),
            'joining_date' => $this->faker->date,
            'photo'        => ( "/$photo_path/" ) . $this->faker->image( public_path( $photo_path ), 320, 240, null, false ),
        ];
    }
}

