<?php

namespace App\Http\Controllers;

use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $query = $request->input('search');
        
        $products = $query
            ? $this->productService->searchProducts($query)
            : $this->productService->getAllProducts(10);

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unity_value' => 'required|numeric|min:0',
        ]);

        $this->productService->createProduct($validated);

        return redirect()->route('products.index')
            ->with('success', 'Produto criado com sucesso!');
    }

    public function show(int $id)
    {
        $data = $this->productService->getProductWithStats($id);
        return view('products.show', $data);
    }

    public function edit(int $id)
    {
        $product = $this->productService->getProductById($id);
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, int $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'unity_value' => 'required|numeric|min:0',
        ]);

        $this->productService->updateProduct($id, $validated);

        return redirect()->route('products.index')
            ->with('success', 'Produto atualizado com sucesso!');
    }

    // public function destroy(int $id)
    // {
    //     $this->productService->deleteProduct($id);

    //     return redirect()->route('products.index')
    //         ->with('success', 'Produto deletado com sucesso!');
    // }
}