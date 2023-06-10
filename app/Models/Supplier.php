<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Request;

class Supplier extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'name', 'email', 'phone', 'address', 'photo', 'shopname',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    const validation_rules = [
        'name'     => ['required', 'regex:/^[\p{L} ]+$/u', 'min:2', 'max:255'],
        'email'    => ['required', 'string', 'email', 'regex:/\.\pL{2,6}$/u', 'max:30', 'unique:employees'],
        'address'  => ['required', 'string', 'regex:/^(\p{L}|,|\.|[0-9]| )+$/u', 'min:5', 'max:255'],
        'shopname' => ['required', 'regex:/^[\p{L} ]+$/u', 'min:2', 'max:255'],
        'phone'    => ['required', 'regex:/^\+?([0-9]|\(|\)|-| )+$/']
    ];

}
