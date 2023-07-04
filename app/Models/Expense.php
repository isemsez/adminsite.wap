<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends ModelCommon
{
    use HasFactory;

    protected $fillable = ['details', 'amount', 'expense_date'];

    protected $hidden = ['created_at', 'updated_at'];

    /**
     * Validate incoming data against validation rules.
     *
     * @return array|null
     */
    public static function validate_data(): ?array
    {
        $validation_rules = [
            'details'      => ['required', 'string',],
            'amount'       => ['required', 'integer', 'min:100', 'max:10000'],
            'expense_date' => ['date']
        ];

        return static::validate_form_data($validation_rules);
    }
}
