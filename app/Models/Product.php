<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'unity_value'
    ];
public function sales()
{
    return $this->belongsToMany(Sale::class, 'product_sale')
    ->withPivot(['quantity', 'unit_price', 'subtotal'])
    ->withTimestamps(false)
    ->orderByDesc('sales.created_at');
}
}