<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductStock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'qty',
        'reference_type',
        'reference_id',
        'note',
    ];

    protected $casts = [
        'qty' => 'integer',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
