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
    public static function validate_data(string $scenario = 'create'): ?array
    {
        $unique = $scenario == 'update' ? null : 'unique:employees';

        $validation_rules = [
            'name'         => ['required', 'regex:/^[\p{L}\. ]+$/u', 'min:2'],
            'email'        => ['required', 'email', 'regex:/\.\p{L}{2,6}$/u', 'max:30', $unique],
            'address'      => ['required', 'regex:/^[\p{L},.\d\- ]+$/u', 'min:5'],
            'salary'       => ['required', 'integer', 'min:100', 'max:10000'],
            'joining_date' => ['required', 'date', 'before:tomorrow'],
            'phone'        => ['required', 'regex:/^\+?([\d\(\)-\. ]|)+$/'],
            'photo'        => [static::photo_validation_rule(),],
        ];

        return static::validate_form_data( $validation_rules );
    }

}
