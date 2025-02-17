<?php

namespace App\Http\Controllers;

use App\Models\ContaBancaria;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SaldoController extends Controller
{
    public function getSaldo($id)
    {
        $contaBancaria = ContaBancaria::where('user_id', $id)
            ->select('saldo')
            ->first();

        if (!$contaBancaria) {
            return response()->json(['error' => 'Conta bancária não encontrada.'], 404);
        }

        return response()->json(['saldo' => $contaBancaria->saldo]);
    }

}
