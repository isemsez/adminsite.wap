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


    protected $validation_rules = [
        'name'     => [ 'required', 'regex:/^[\p{L} ]+$/u', 'min:2', 'max:255' ],
        'email'    => [ 'required', 'string', 'email', 'regex:/\.\pL{2,6}$/u', 'max:30', 'unique:employees' ],
        'address'  => [ 'required', 'string', 'regex:/^(\p{L}|,|\.|[0-9]| )+$/u', 'min:5', 'max:255' ],
        'shopname' => [ 'required', 'regex:/^[\p{L} ]+$/u', 'min:2', 'max:255' ],
        'phone'    => [ 'required', 'regex:/^\+?([0-9]|\(|\)|-| )+$/' ]
    ];


    /**
     * Add custom ('photo') rule to $validation_rules.
     */
    public function __construct()
    {
        parent::__construct();
        $this->validation_rules += [ 'photo' =>
            [ function ($attribute, $value, $fail) {
                if ( $value ) {
                    $photo = Image::make( $value );
                    $photo_mime = explode( '/', $photo->mime() );
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
            }],
        ];
    }


    /**
     * Validate incoming form data.
     *
     * @return array|null
     */
    public function validate_data(): ?array
    {
        return $this->validate_form_data($this->validation_rules);
    }

}
