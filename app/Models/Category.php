<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\JsonResponse;

class Category extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'category_name',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    /**
     * Validate incoming data.
     *
     * @return array|void
     */
    public static function validate_data(string $scenario = 'store'): ?array
    {
        $unique = $scenario == 'update' ? null : 'unique:categories';
        $validation_rules = [
            'category_name' => [
                'string', 'min:3', 'max:20', $unique, function ($attribute, $value, $fail) {
                    if ( !preg_match( '/^[\p{L} ]+$/u', $value ) ) {
                        $fail( 'Только буквы и пробел.' );
                    }
                }
            ],
        ];

        return static::validate_form_data( $validation_rules );
    }

    /**
     * @return HasOne
     */
    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
