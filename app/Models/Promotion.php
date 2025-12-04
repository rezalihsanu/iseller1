<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'type',
        'discount_percentage',
        'discount_nominal',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'discount_nominal'   => 'decimal:2',
        'start_date'         => 'datetime',
        'end_date'           => 'datetime',
        'is_active'          => 'boolean',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product');
    }
}
