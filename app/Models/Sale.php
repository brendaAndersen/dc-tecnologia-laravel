<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'total_amount'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function products(): BelongsToMany
    {
     return $this->belongsToMany(Product::class, 'product_sale')
        ->withPivot('quantity', 'unit_price', 'subtotal');
    }
    public function installments()
    {
        return $this->hasMany(Installment::class);
    }
}