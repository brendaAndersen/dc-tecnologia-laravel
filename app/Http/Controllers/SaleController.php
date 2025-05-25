<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class SaleController extends Controller {
   public function index(Request $request)
    {
        $query = Sale::query()->with('client', 'products');

        if ($search = $request->input('search')) {
            $query->whereHas('client', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $sales = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        return view('sales.create', [
            'clients' => Client::all(),
            'products' => Product::all()
        ]);
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'client_id' => 'required|exists:clients,id',
                'total_amount' => 'required|numeric|min:0.01',
                'payment_type' => 'required|string|in:01,02,03,04,05',
                'installments_count' => 'required|integer|min:1',
                'installments' => 'required|array|min:1',
                'installments.*.due_date' => 'required|date',
                'installments.*.amount' => 'required|numeric|min:0.01',
                'products' => 'required|array|min:1',
                'products.*.id' => 'required|exists:products,id',
                'products.*.quantity' => 'required|integer|min:1',
                'products.*.unit_price' => 'required|numeric|min:0.01',
                'products.*.subtotal' => 'required|numeric|min:0.01'
            ]);

            return DB::transaction(function () use ($validated) {
                // Verifica o total dos produtos
                $calculatedTotal = collect($validated['products'])->sum('subtotal');
                
                if (abs($calculatedTotal - $validated['total_amount']) > 0.01) {
                    abort(422, 'O total calculado não corresponde ao valor enviado');
                }

                // Verifica o total das parcelas
                $installmentsTotal = collect($validated['installments'])->sum('amount');
                
                 if (abs($installmentsTotal - $validated["total_amount"]) > 0.01) {
                    return back()
                        ->withErrors([
                            'installments' => 'A soma das parcelas (R$ '.number_format($installmentsTotal, 2, ',', '.').') 
                        não corresponde ao total da venda (R$ '.number_format($validated["total_amount"], 2, ',', '.').')'
                        ])
                        ->withInput()
                        ->with('error', 'Ajuste os valores das parcelas antes de continuar');
                }

                // Cria a venda
                $sale = Sale::create([
                    'client_id' => $validated['client_id'],
                    'total_amount' => $validated['total_amount'],
                    'payment_type' => $validated['payment_type']
                ]);

                // Adiciona os produtos
                foreach ($validated['products'] as $productData) {
                    $productId = $productData['id'];

                    DB::table('product_sale')->insert([
                        'product_id' => $productId,
                        'sale_id' => $sale->id,
                        'quantity' => $productData['quantity'],
                        'unit_price' => $productData['unit_price'],
                        'subtotal' => $productData['subtotal'],
                    ]);

                    // Atualiza o estoque
                    Product::where('id', $productId)
                        ->decrement('quantity', $productData['quantity']);
                }

                // Adiciona as parcelas
                foreach ($validated['installments'] as $index => $installmentData) {
                    $sale->installments()->create([
                        'number' => $index + 1,
                        'due_date' => $installmentData['due_date'],
                        'amount' => $installmentData['amount'],
                        'payment_type' => $validated['payment_type']
                    ]);
                }

                return redirect()->route('sales.index')
                    ->with('success', 'Venda registrada com sucesso!');
            });

        } catch(Exception $e) {
            Log::error('Erro ao salvar venda: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Ocorreu um erro ao processar a venda: ' . $e->getMessage()])
                        ->withInput();
        }
    }
   public function edit($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        $clients = Client::all();
        $products = Product::all();
        
        $sale->load('products');
        
        return view('sales.edit', compact('sale', 'clients', 'products'));
    }

    public function update(Request $request, $saleId)
    {
        try{
            $sale = Sale::findOrFail($saleId); 

            DB::transaction(function () use ($request, $saleId) {
                $sale = Sale::findOrFail($saleId); 
                
                $sale->update([
                    'client_id' => $request->client_id,
                    'sale_date' => $request->sale_date,
                    'total_amount' => 0, 
                ]);
    
                $productsData = [];
                $totalAmount = 0;
    
                foreach ($request->products as $product) {
                    $subtotal = $product['quantity'] * $product['unit_price'];
                    $productsData[$product['id']] = [
                        'quantity' => $product['quantity'],
                        'unit_price' => $product['unit_price'],
                        'subtotal' => $subtotal,
                    ];
                    $totalAmount += $subtotal;
                }
    
                $sale->products()->sync($productsData);
                $sale->update(['total_amount' => $totalAmount]);
            });
    
            return redirect()->route('sales.show', $sale)
                ->with('success', 'Venda atualizada com sucesso!');
        } catch(Exception $e) {
            Log::error('Erro ao editar venda: ' . $e->getMessage());
            return response()->json([
                "data" => "Ocorreu um erro: " . $e
            ]);
        }
    }   
   public function show($id)
    {
        $sale = Sale::findOrFail($id);
        return view('sales.show', compact('sale'));
    }
  
    public function destroy($id)
    {
        try{

            DB::table('product_sale')->where('sale_id', $id)->delete();
        
            DB::table('sales')->where('id', $id)->delete();
        
            return redirect()->route('sales.index')
                ->with('success', 'Venda excluída com sucesso!');
        } catch(Exception $e) {
            Log::error('Erro ao salvar venda: ' . $e->getMessage());
            return response()->json([
                "data" => "Ocorreu um erro: " . $e
            ]);
        }
    }   


    public function downloadPdf($saleId)
    {
        $sale = Sale::findOrFail($saleId);
        
        $sale->load(['client', 'products', 'installments']);
        
        Carbon::setLocale('pt_BR');
        $now = Carbon::now('America/Sao_Paulo');
        
        $data = [
            'sale' => $sale,
            'title' => 'Resumo da Venda #' . $sale->id,
            'date' => $now->format('d/m/Y H:i:s')
        ]; 
        
        $pdf = Pdf::loadView('sales.pdf', $data);
        $filename = 'resumo-venda-' . $sale->id . '.pdf';
        
        return $pdf->download($filename);
    }
}