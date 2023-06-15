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
        $this_class_name = ( new ReflectionClass( get_class( $this ) ) )->getShortName();
        $entity_name = strtolower( preg_replace( '/Factory$/', '', $this_class_name ) );

        $photo_path = "backend/$entity_name";
        Controller::create_dir_if_necessary( public_path( $photo_path ) );

        return [
            'name'         => $this->faker->name,
            'email'        => $this->faker->safeEmail,
            'address'      => $this->faker->address,
            'phone'        => $this->faker->phoneNumber,
            'salary'       => $this->faker->numberBetween( 1000, 6000 ),
            'joining_date' => $this->faker->date,
            'photo'        => ( "/$photo_path/" ) . $this->faker->image( public_path( $photo_path ), 320, 240, null, false )
        ];
    }
}

