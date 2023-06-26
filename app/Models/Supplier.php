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
     * @param string $scenario
     * @return array|null
     */
    public static function validate_data(string $scenario = 'create'): ?array
    {
        $unique = $scenario == 'update' ? null : 'unique:suppliers';

        $validation_rules = [
            'name'     => [ 'required', 'regex:/^[\p{L}\. ]+$/u', 'min:2' ],
            'email'    => [ 'required', 'email', 'regex:/\.\p{L}{2,6}$/u', 'max:30', $unique ],
            'address'  => [ 'required', 'regex:/^[\p{L},.\d\- ]+$/u', 'min:5' ],
            'shopname' => [ 'required', 'regex:/^[\p{L} ]+$/u', 'min:3' ],
            'phone'    => [ 'required', 'regex:/^\+?([\d\(\)-\. ]|)+$/' ],
            'photo'    =>   static::photo_validation_rule(),
        ];

        return static::validate_form_data($validation_rules);
    }


}
