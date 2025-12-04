<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'unit_id',
        'name',
        'code_barang',
        'description',
        'price',
        'stock',
        'foto_produk',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function promotions()
    {
        return $this->belongsToMany(Promotion::class, 'promotion_product');
    }

    public function stockMovements()
    {
        return $this->hasMany(ProductStock::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
