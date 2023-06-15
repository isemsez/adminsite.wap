<?php

namespace App\Models;

use Closure;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Intervention\Image\Facades\Image;

class Employee extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'address', 'phone', 'salary', 'joining_date', 'photo',
    ];
    protected $hidden = [
        'created_at', 'updated_at',
    ];

    /**
     * Проверка пришедших данных.
     *
     * @return array|Void
     */
    public function validate_data(bool $email_unique = false): ?array
    {
        return static::validate_form_data( $this->validation_rules( $email_unique ) );
    }

    /**
     * Add custom ('photo') rule to $validation_rules.
     * @return array<string|Closure>
     */
    private function validation_rules($email_unique): array
    {
        $unique = $email_unique ? 'unique:employees' : null;

        $validation_rules = [
            'name'         => [ 'required', 'regex:/^[\p{L}\. ]+$/u', 'min:2' ],
            'email'        => [ 'required', 'email', 'regex:/\.\p{L}{2,6}$/u', 'max:30', $unique ],
            'address'      => [ 'required', 'regex:/^[\p{L},.\d\- ]+$/u', 'min:5' ],
            'salary'       => [ 'required', 'integer', 'min:100', 'max:10000' ],
            'joining_date' => [ 'required', 'date', 'before:tomorrow' ],
            'phone'        => [ 'required', 'regex:/^\+?([\d\(\)-\. ]|)+$/' ],
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
