<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'category_id', 'product_name', 'product_code', 'root', 'buying_price', 'selling_price', 'supplier_id', 'buying_date', 'photo', 'product_quantity',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];



    /**
     * Validate incoming data.
     *
     * @return array|void
     */
    public function validate(): ?array
    {
        $validation_rules = [
            'product_name' => 'required',
            'product_code' => 'required|unique:products',
            'category_id' => 'required|integer',
            'supplier_id' => 'required|integer',
            'buying_price' => 'required|float',
            'selling_price' => 'required|float',
            'buying_date' => 'required|date',
            'product_quantity' => 'required|integer'
        ];

        return $this->validate_form_data( $validation_rules );
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }
}
