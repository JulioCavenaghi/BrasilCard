<?php

namespace App\Http\Controllers;

use App\Services\TransacaoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ExtratoController extends Controller
{
    public function __construct(
        protected TransacaoService $transacaoService, 
        protected SessaoController $sessaoController
    ) {}

    public function showExtrato(Request $request)
    {
        if (!$request->wantsJson()) {
            $user = Auth::user();
            $id = $user->id;
        } else {
            $id = $request->route('id');
        }

        $response = $this->sessaoController->getDadosSessao($id);
        $dadosSessao = $response instanceof \Illuminate\Http\JsonResponse ? $response->getData(true) : $response;
        $numero_conta = $dadosSessao['numero_conta'] ?? null;

        if (!$numero_conta) {
            return response()->json([
                'status' => false,
                'message' => 'Número da conta não encontrado.'
            ], 400);
        }

        $transacoesData = $this->transacaoService->listarTransacoes($numero_conta);

        if ($transacoesData['status']) {
            $transacoes = $transacoesData['transacoes'];

            if ($request->wantsJson()) {
                return response()->json([
                    'status' => true,
                    'transacoes' => $transacoes
                ]);
            }

            return view('home', compact('transacoes'));
        }

        if ($request->wantsJson()) {
            return response()->json([
                'status' => false,
                'transacoes' => []
            ]);
        }

        return view('home', ['transacoes' => []]);
    }
}