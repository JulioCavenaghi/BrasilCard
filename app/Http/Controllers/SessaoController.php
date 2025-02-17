<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\DadosUsuarioService;

class SessaoController extends Controller
{
    public function __construct(
        protected Request $request,
        protected DadosUsuarioService $dadosUsuarioService,
    ){}
    
    public function getDadosSessao($id = null)
    {

        $userId = $id ?? Auth::id();

        if (!$userId) {
            return response()->json(['error' => 'Usuário não autenticado.'], 401);
        }

        try {

            $dados = $this->dadosUsuarioService->obterDadosUsuario($userId);

            if (!$dados['success']) {
                return response()->json(['error' => $dados['message']], 400);
            }

            $dados = $dados['dados'];

            return $this->responseFormat($dados);

        } catch (\Exception $e) {

            return response()->json(['error' => 'Erro ao processar a solicitação.'], 500);
        }
    }

    private function responseFormat($dados)
    {
        if ($this->request->wantsJson()) {
            return response()->json([
                'id' => $dados['id'],
                'name' => $dados['name'],
                'numero_conta' => $dados['numero_conta']
            ]);
        }

        return [
            'id' => $dados['id'],
            'name' => $dados['name'],
            'numero_conta' => $dados['numero_conta']
        ];
    }

}