<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
class Client extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'cpf'];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }
}