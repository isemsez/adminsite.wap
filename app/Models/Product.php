<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends ModelCommon
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'root',
        'category_id',
        'supplier_id',
        'buying_price',
        'selling_price',
        'buying_date',
        'photo',
        'product_quantity',
    ];

    protected $hidden = [
        'created_at', 'updated_at',
    ];


    /**
     * Validate incoming data.
     *
     * @param string $scenario
     * @return array|void
     */
    public static function validate_data(string $scenario = 'store'): ?array
    {
        $tmp = $scenario == 'update' ? '' : '|unique:products';

        $validation_rules = [
            'product_name'     => 'required',
            'product_code'     => "required{$tmp}",
            'category_id'      => 'required|integer',
            'supplier_id'      => 'required|integer',
            'buying_price'     => 'required|integer',
            'selling_price'    => 'required|integer',
            'buying_date'      => 'required|date',
            'product_quantity' => 'required|integer'
        ];
        $validation_rules += ModelCommon::photo_validation_rule();

        return ModelCommon::validate_form_data( $validation_rules );
    }


    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
