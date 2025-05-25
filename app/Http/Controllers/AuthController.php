<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected function authenticated(Request $request, $user)
    {
        return redirect()->intended('/sales');
    }
     public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'cpf' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    $cleaned = preg_replace('/[^0-9]/', '', $value);
                    
                    if (strlen($cleaned) != 11) {
                        $fail('O CPF deve conter 11 dígitos.');
                    }
                },
            ],
        ], [
            'cpf.required' => 'O campo CPF é obrigatório.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $cpf = preg_replace('/[^0-9]/', '', $request->cpf);
        $client = Client::where('cpf', $cpf)->first();

        if (!$client) {
            return back()
                ->withInput()
                ->withErrors(['cpf' => 'Cliente não encontrado!']);
        }

        auth()->guard('client')->login($client); 
        return redirect()->route('sales.index');
    }
}