<?php

namespace App\Services;

use App\Models\ContaBancaria;
use App\Models\Transacao;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

class TransacaoService
{
    public function registrarTransacao(array $dados)
    {
        try {
            DB::beginTransaction();

            if (isset($dados['id'])) {
                $this->reverterTransacao($dados['id']);
            }

            $conta = $this->buscarConta($dados['numero_conta']);
            $novoSaldo = $this->calcularNovoSaldo($conta->saldo, $dados);
            $transacao = $this->criarTransacao($dados);
            $this->atualizarSaldoConta($dados['numero_conta'], $novoSaldo);

            if (in_array($dados['tipo'], [4, 5, 6])) {
                $novoTipo = [4 => 8, 5 => 3, 6 => 7];
                $dados['tipo'] = $novoTipo[$dados['tipo']];

                if ($dados['tipo'] == 8) {
                    $dados['numero_conta'] = Transacao::where('transacao_destino', $dados['id'])
                                                      ->where('id', '!=', $dados['id'])
                                                      ->first()?->numero_conta;
                } elseif ($dados['tipo'] == 7) {
                    $dados['numero_conta'] = Transacao::where('transacao_origem', $dados['id'])
                                                      ->where('id', '!=', $dados['id'])
                                                      ->first()?->numero_conta;
                } elseif ($dados['tipo'] == 3) {
                    $dados['numero_conta'] = $dados['numero_conta_destino'];
                }

                $conta = $this->buscarConta($dados['numero_conta']);
                $novoSaldo = $this->calcularNovoSaldo($conta->saldo, $dados);
                $transacao = $this->criarTransacao($dados);

                $transacao_origem = $this->getIdTransacao(5);
                $transacao_destino = $this->getIdTransacao(3);

                $this->atualizarTransacao($transacao_origem, $transacao_origem, $transacao_destino);
                $this->atualizarTransacao($transacao_destino, $transacao_origem, $transacao_destino);
                $this->atualizarSaldoConta($dados['numero_conta'], $novoSaldo);

                if (in_array($dados['tipo'], [7, 8])) {
                    $this->reverterTransacao($transacao_origem);
                    $this->reverterTransacao($transacao_destino);
                }
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Transação registrada com sucesso!',
                'transacao' => $transacao
            ];
        } catch (QueryException $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Erro ao registrar transação no banco de dados.',
                'message' => $e->getMessage()
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'error' => 'Erro inesperado ao registrar transação.',
                'message' => $e->getMessage()
            ];
        }
    }

    private function reverterTransacao($id)
    {
        Transacao::where('id', $id)->update(['transacao_revertida' => 1]);
    }

    private function buscarConta($numeroConta)
    {
        $conta = ContaBancaria::lockForUpdate()->where('numero_conta', $numeroConta)->first();
        
        if (!$conta) {
            throw new Exception("Conta bancária não encontrada.");
        }

        return $conta;
    }

    private function calcularNovoSaldo($saldoAtual, $dados)
    {
        $novoSaldo = $saldoAtual;
        $valor = $dados['valor'];

        if (in_array($dados['tipo'], [2, 4, 5, 6]) && $saldoAtual < $valor) {
            throw new Exception("Saldo insuficiente para realizar esta operação.");
        }

        switch ($dados['tipo']) {
            case 1:
            case 3:
            case 6:
            case 8:
                $novoSaldo += $valor;
                break;
            case 2:
            case 4:
            case 5:
            case 7:
                $novoSaldo -= $valor;
                break;
        }

        return $novoSaldo;
    }

    private function criarTransacao($dados)
    {
        return Transacao::create([
            'numero_conta'      => $dados['numero_conta'],
            'descricao'         => $this->getDescricaoTransacao($dados['tipo']),
            'valor'             => $dados['valor'],
            'tipo'              => $dados['tipo'],
            'transacao_origem'  => $dados['transacao_origem']   ?? '',
            'transacao_destino' => $dados['transacao_destino']  ?? '',
        ]);
    }

    private function atualizarSaldoConta($numeroConta, $novoSaldo)
    {
        ContaBancaria::where('numero_conta', $numeroConta)->update(['saldo' => $novoSaldo]);
    }

    private function getDescricaoTransacao($tipo)
    {
        return match ($tipo) {
            1 => 'Depósito de valores',
            2 => 'Resgate do valor depositado',
            3 => 'Transferência recebida',
            4 => 'Reembolso realizado',
            5 => 'Transferência Realizada',
            6 => 'Estorno realizado',
            7 => 'Estorno debitado',
            8 => 'Reembolso recebido',
            default => 'Transação desconhecida',
        };
    }

    public function listarTransacoes($numeroConta)
    {
        try {
            $transacoes = Transacao::select('id', 'numero_conta', 'descricao', 'valor', 'tipo', 'transacao_revertida', 'created_at')
                ->where('numero_conta', $numeroConta)
                ->orderBy('created_at', 'desc')
                ->get();

            return [
                'success' => true,
                'status' => true,
                'transacoes' => $transacoes->map(fn ($transacao) => [
                    'id' => $transacao->id,
                    'numero_conta' => $transacao->numero_conta,
                    'descricao' => $transacao->descricao,
                    'valor' => number_format($transacao->valor, 2, '.', ''),
                    'tipo' => $transacao->tipo,
                    'transacao_revertida' => $transacao->transacao_revertida,
                    'data' => $transacao->created_at->format('Y-m-d H:i:s')
                ])
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro ao buscar transações.',
                'error' => $e->getMessage()
            ];
        }
    }

    public function getIdTransacao($tipo)
    {
        return Transacao::where('tipo', $tipo)
                        ->orderBy('id', 'desc')
                        ->first()?->id;
    }

    public function atualizarTransacao($id, $transacao_origem, $transacao_destino)
    {
        $registro = Transacao::find($id);

        if ($registro) {
            $registro->update([
                'transacao_origem' => $transacao_origem,
                'transacao_destino' => $transacao_destino
            ]);
        }
    }
}