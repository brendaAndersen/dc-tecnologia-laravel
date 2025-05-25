<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Sale;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    public function getAllProducts(int $perPage = null): LengthAwarePaginator|Collection
    {
        return $perPage ? Product::paginate($perPage) : Product::all();
    }

    public function createProduct(array $data): Product
    {
        return Product::create([
            'name' => $data['name'],
            'quantity' => $data['quantity'],
            'unity_value' => $data['unity_value']
        ]);
    }

    public function getProductById(int $id): Product
    {
        return Product::findOrFail($id);
    }
public function getProductWithStats(int $id): array
{
    $product = Product::findOrFail($id);
  
    return [
        'product' => $product,
        'stats' => [
            'total_sales' => $product->sales()->count(),
            'total_amount' => $product->sales()->sum('product_sale.subtotal'),
            'average_sale' => $product->sales()->avg('product_sale.subtotal'),
        ]
    ];
}

    public function updateProduct(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function searchProducts(string $query, int $perPage = 10): LengthAwarePaginator
    {
        return Product::where('name', 'like', "%$query%")
            ->orWhere('description', 'like', "%$query%")
            ->paginate($perPage);
    }
}