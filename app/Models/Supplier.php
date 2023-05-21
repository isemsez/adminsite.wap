<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','email','phone','address','photo','shopname',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];
}
