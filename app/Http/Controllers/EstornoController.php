<?php

namespace App\Http\Controllers;

use App\Services\TransacaoService;
use Illuminate\Http\Request;

class EstornoController extends Controller
{
    public function __construct(
        protected Request $request,
        protected TransacaoService $transacaoService
    ) {}

    public function solicitarEstorno()
    {
        $dadosTransacao = $this->request->only(['id','numero_conta', 'valor', 'tipo']);

        $resultado = $this->transacaoService->registrarTransacao($dadosTransacao);

        if (isset($resultado['error'])) {
            return response()->json($resultado, 400);
        }

        return response()->json([
            'message' => 'Estorno realizado com sucesso!',
            'transacao' => $resultado
        ], 201);
    }
}
