<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
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
     * @return JsonResponse|void
     */
    public function validate(): ?JsonResponse
    {
        $validation_rules = [
            'category_name' => [
                'string', 'min:3', 'max:20', function ($attribute, $value, $fail) {
                    if ( !preg_match( '/^[\p{L} ]+$/u', $value ) ) {
                        $fail( 'Только буквы и пробел.' );
                    }
                }
            ],
        ];

        $validator = $this->validate_form_data( $validation_rules );

        if ( isset( $validator['failed'] ) ) {
            return $validator['validation_failed_json_response'];
        }

    }
}
