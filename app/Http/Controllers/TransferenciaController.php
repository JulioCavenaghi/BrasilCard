<?php

namespace App\Http\Controllers;

use App\Services\TransacaoService;
use Illuminate\Http\Request;

class TransferenciaController extends Controller
{
    public function __construct(
        protected Request $request,
        protected TransacaoService $transacaoService,
        protected SessaoController $sessaoController
    ){}

    public function view()
    {
        return view('transferencia');
    }

    public function store()
    {
        $numero_conta = $this->sessaoController->getDadosSessao()['numero_conta'];

        $this->request->merge([
            'numero_conta' => $numero_conta ?? null,
        ]);

        $dadosValidados = $this->request->validate([
            'numero_conta' => 'required|string|exists:contas_bancarias,numero_conta',
            'numero_conta_destino' => 'required|string|exists:contas_bancarias,numero_conta',
            'valor' => 'required|numeric|min:10',
        ]);

        $dadosValidados['tipo']  = 5;

        $resultado = $this->transacaoService->registrarTransacao($dadosValidados);

        if (!$resultado['success']) {
            return redirect()->back()->withErrors($resultado['message'])->withInput();
        }

        return redirect()->route('transferencia.index')->with('success', 'Transferência realizada com sucesso!');
    }
}
