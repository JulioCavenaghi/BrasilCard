<nav x-data="saldoComponent" class="bg-black border-b border-gray-300 w-full">
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center w-full">
            <div class="flex items-center">
                <button @click="open = ! open" class="p-2 rounded-md bg-white text-gray-800 hover:bg-gray-200 focus:bg-gray-200 focus:outline-none">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="open ? 'hidden' : 'block'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        <path :class="open ? 'block' : 'hidden'" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            <!-- Usando a classe personalizada navbar-right -->
            <div class="navbar-right text-white">
                <span class="font-semibold">Nome: <span x-text="nome"></span></span>
                <span class="font-semibold"> | Nº Conta: <span x-text="numeroConta"></span></span>
                <span class="font-semibold"> | Saldo: <span x-text="saldo"></span></span>
            </div>
        </div>
    </div>

    <!-- Menu Lateral -->
    <div x-show="open" class="fixed inset-y-0 right-0 transform translate-x-4 w-64 bg-black text-white p-5 shadow-lg transition-transform" x-transition>
        <ul class="mt-4 space-y-4">
            <li><a href="{{ route('home') }}" class="block px-4 py-2 hover:bg-gray-700">Home</a></li>
            <li><a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-700">Perfil</a></li>
            <li><a href="{{ route('deposito.index') }}" class="block px-4 py-2 hover:bg-gray-700">Depósito</a></li>
            <li><a href="{{ route('transferencia.index') }}" class="block px-4 py-2 hover:bg-gray-700">Transferência</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-700">Sair</button>
                </form>
            </li>
        </ul>
    </div>
</nav>

<script src="{{ asset('js/navigation.js') }}"></script>  