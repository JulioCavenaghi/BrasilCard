<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Página de login</title>
</head>

<body class="bg-white flex items-center justify-center min-h-screen">
    <div class="w-full max-w-md bg-white text-black rounded-2xl shadow-2xl p-8">
        <h2 class="text-3xl font-extrabold text-center text-black mb-2">Acesse sua conta</h2>
        <p class="text-center text-gray-600 mb-6">Informe seu e-mail e senha para acessar sua conta</p>

        <!-- Exibição de erros gerais -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                <strong class="font-bold">Erro!</strong>
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Email Address -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">E-mail</label>
                <x-text-input id="email"
                    class="w-full px-4 py-2 mt-1 bg-gray-100 text-black rounded-xl focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Senha</label>
                <x-text-input id="password"
                    class="w-full px-4 py-2 mt-1 bg-gray-100 text-black rounded-xl focus:ring-2 focus:ring-purple-500 focus:outline-none"
                    type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <button type="submit"
                class="w-full py-2 mt-4 bg-purple-600 hover:bg-purple-700 rounded-xl text-white font-semibold text-center transition-all duration-300">
                Entrar
            </button>
        </form>

        <p class="text-center text-sm text-gray-600 mt-6">
            Não possui uma conta? <a href="{{ route('register') }}" class="text-purple-500 hover:underline">Cadastre-se</a>
        </p>
    </div>
</body>

</html>