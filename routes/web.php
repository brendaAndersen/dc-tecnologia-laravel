<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\SaleController;
use App\Models\Client;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

// Auth
Route::middleware(['auth:client'])->group(function() {    Route::get('/sales', function () {
        return view('sales');
    })->name('sales');
    // Products
    Route::resource('products', ProductController::class);
    
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
    Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
    Route::resource('/sales', SaleController::class);
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    
    // Clients
    Route::resource('clients', ClientController::class);
    Route::get('clients/{client}/sales', [ClientController::class, 'sales'])
    ->name('clients.sales');
    
    Route::get('/clients/search', function(Request $request) {
        $query = $request->input('query');
        
        return Client::where('name', 'like', "%$query%")
        ->orWhere('email', 'like', "%$query%")
        ->limit(10)
        ->get(['id', 'name', 'email']);
    });
    
    Route::get('/sales/{sale}/download', [SaleController::class, 'downloadPdf'])
    ->name('sales.download');
    
});

// Login
Route::get('/', function () {
    return view('auth.login');
})->name('login'); 

Route::post('/', [AuthController::class, 'login']);