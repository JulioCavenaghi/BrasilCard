<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Transferência de Valores') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">                   


                    @if (session('success'))
                        <div class="text-green-600 p-4 rounded-md mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('transferencia.store') }}">
                        @csrf
                        
                        <div>
                            <label for="numero_conta_destino" class="block text-lg font-bold text-black">Número da Conta</label>
                            <input type="text" id="numero_conta_destino" name="numero_conta_destino" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
                        </div>

                        <div class="mt-4">
                            <label for="valor" class="block text-lg font-bold text-black">Valor da Transferência</label>
                            <input type="text" id="valor" name="valor" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2" oninput="formatarValor(this)">
                        </div>
                        
                        <div class="mt-4 w-full">
                            <button type="submit" class="w-full bg-black text-white px-4 py-2 rounded-md text-lg font-semibold hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-opacity-50">
                                Transferir
                            </button>
                        </div>
                    </form>

                    @if ($errors->any())
                        <div class="text-red-600 p-4 rounded-md mb-4">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <script src="{{ asset('js/transferencia.js') }}"></script>  

</x-app-layout>
