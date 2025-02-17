<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h2 class="text-2xl font-semibold text-center text-gray-800 dark:text-gray-200 mb-6">
                        Extrato Bancário
                    </h2>

                    <div class="space-y-8">
                        <div class="bg-white p-4 rounded-lg shadow-md">
                            <div class="border-b border-gray-300 mb-4"></div>
                            <div class="space-y-3" id="lista-transacoes">
                                @foreach($transacoes as $transacao)
                                    <div class="flex flex-col mb-4">
                                        <div class="flex justify-between items-center">
                                            <div class="flex flex-col">
                                                <span class="text-gray-800">{{ $transacao['descricao'] }}</span>
                                                <span class="text-sm text-gray-500">
                                                    Realizado em: {{ \Carbon\Carbon::parse($transacao['data'])->format('d/m/Y') }}
                                                </span>
                                            </div>
                                            <div class="text-right">
                                                @php
                                                    $classeTipo = '';
                                                    $sinal = '';
                                                    $botaoTexto = '';
                                                    $botaoAcao = '';

                                                    switch($transacao['tipo']) {
                                                        case 1:
                                                            $classeTipo = 'text-green-600';
                                                            $sinal = '+';
                                                            $botaoTexto = 'Solicitar Resgate';
                                                            $botaoAcao = "solicitarResgate";
                                                            break;
                                                        case 2:
                                                            $classeTipo = 'text-red-600';
                                                            $sinal = '-';
                                                            break;
                                                        case 3:
                                                            $classeTipo = 'text-green-600';
                                                            $sinal = '+';
                                                            $botaoTexto = 'Reembolsar';
                                                            $botaoAcao = "reembolsar";
                                                            break;
                                                        case 4:
                                                            $classeTipo = 'text-red-600';
                                                            $sinal = '-';
                                                            break;
                                                        case 5:
                                                            $classeTipo = 'text-red-600';
                                                            $sinal = '-';
                                                            $botaoTexto = 'Solicitar Estorno';
                                                            $botaoAcao = "solicitarEstorno";
                                                            break;
                                                        case 6:
                                                            $classeTipo = 'text-green-600';
                                                            $sinal = '+';
                                                            break;
                                                        case 7:
                                                            $classeTipo = 'text-red-600';
                                                            $sinal = '-';
                                                            break;
                                                        case 8:
                                                            $classeTipo = 'text-green-600';
                                                            $sinal = '+';
                                                            break;
                                                    }
                                                @endphp

                                                <span class="{{ $classeTipo }} font-semibold">
                                                    {{ $sinal }} R$ {{ number_format($transacao['valor'], 2, ',', '.') }}
                                                </span>
                                            </div>
                                        </div>

                                        <!-- Passando o transacao_id diretamente para as funções -->
                                        <input type="hidden" name="transacao_id" value="{{ $transacao['id'] }}">

                                        @if($transacao['transacao_revertida'] == 1)
                                            <div class="mt-2">
                                                <button 
                                                    class="w-full bg-gray-400 text-gray-600 px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50" disabled>
                                                    Não é mais possível realizar esta operação
                                                </button>
                                            </div>
                                        @else
                                            @if($botaoTexto)
                                                <div class="mt-2">
                                                    <button 
                                                        class="w-full bg-black text-white px-4 py-2 rounded-md text-sm font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50"
                                                        onclick="{{ $botaoAcao }}('{{ $transacao['descricao'] }}', {{ $transacao['valor'] }}, {{ $transacao['id'] }})">
                                                        {{ $botaoTexto }}
                                                    </button>
                                                </div>
                                            @endif
                                        @endif

                                        <div class="border-b border-gray-200 mt-4"></div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>  
    
</x-app-layout>