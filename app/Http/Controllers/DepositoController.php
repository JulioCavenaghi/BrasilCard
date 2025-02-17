<?php

namespace App\Http\Controllers;

use App\Services\TransacaoService;
use Illuminate\Http\Request;

class DepositoController extends Controller
{
    public function __construct(
        protected Request $request,
        protected TransacaoService $transacaoService,
        protected SessaoController $sessaoController
    ){}

    public function view()
    {
        return view('deposito');
    }

    public function store()
    {
        $numero_conta = $this->sessaoController->getDadosSessao()['numero_conta'];

        $this->request->merge([
            'numero_conta' => $numero_conta ?? null,
        ]);

        $dadosValidados = $this->request->validate([
            'numero_conta' => 'required|string|exists:contas_bancarias,numero_conta',
            'valor' => 'required|numeric|min:10',
        ]);

        $dadosValidados['tipo']  = 1;

        $resultado = $this->transacaoService->registrarTransacao($dadosValidados);

        if (!$resultado['success']) {
            return redirect()->back()->withErrors($resultado['message'])->withInput();
        }

        return redirect()->route('deposito.index')->with('success', 'Depósito realizado com sucesso!');
    }

}
