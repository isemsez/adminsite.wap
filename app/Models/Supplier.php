<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class Supplier extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'photo', 'shopname',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Validate incoming form data.
     *
     * @param bool $email_unique
     * @return array|null
     */
    public function validate_data(bool $email_unique = false): ?array
    {
        return $this->validate_form_data( $this->validation_rules( $email_unique ) );
    }


    /**
     * Get validation rules.
     *
     * @param bool $email_unique
     * @return array
     */
    private function validation_rules(bool $email_unique): array
    {
        $unique = $email_unique ? 'unique:suppliers' : null;

        $validation_rules = [
            'name'     => [ 'required', 'regex:/^[\p{L}\. ]+$/u', 'min:2' ],
            'email'    => [ 'required', 'email', 'regex:/\.\p{L}{2,6}$/u', 'max:30', $unique ],
            'address'  => [ 'required', 'regex:/^[\p{L},.\d\- ]+$/u', 'min:5' ],
            'shopname' => [ 'required', 'regex:/^[\p{L} ]+$/u', 'min:3' ],
            'phone'    => [ 'required', 'regex:/^\+?([\d\(\)-\. ]|)+$/' ],
        ];

        $validation_rules += [
            'photo' => function ($attribute, $value, $fail) {
                if ( $value ) {

                    $photo_mime = explode( '/', Image::make( $value )->mime() );
                    if ( $photo_mime[0] != 'image'
                        or !in_array( $photo_mime[1],
                            [ 'jpg', 'jpeg', 'png', 'bmp', 'gif' ] ) ) {

                        $fail( 'Отправленный вами файл должен быть изображением (jpg,png,gif,bmp).' );
                    }
                    // photo comes as string - "reader.readAsDataURL"
                    if ( strlen( $value ) > 1400000 ) {
                        $fail( 'Файл больше 1Мб.' );
                    }
                }
                return true;
            }
        ];
        return $validation_rules;
    }

}
