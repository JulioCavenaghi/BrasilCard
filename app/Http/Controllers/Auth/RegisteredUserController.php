<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ContaBancaria;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Support\Str;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf' => ['required', 'string']
        ]);

        DB::beginTransaction();

        try {

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->input('password'))
            ]);

            $numero_conta = preg_replace('/\D/', '', $request->cpf);

            if (strlen($numero_conta) < 11) {
                DB::rollBack();
                return back()->withErrors(['error' => 'CPF inválido.']);
            }

            $numero_conta = substr($numero_conta, 0, 10) . '-' . substr($numero_conta, 10);

            if (ContaBancaria::where('numero_conta', $numero_conta)->exists()) {
                DB::rollBack();
                return back()->withErrors(['error' => 'Número de conta já cadastrado para este CPF.']);
            }

            ContaBancaria::create([
                'user_id' => $user->id,
                'numero_conta' => $numero_conta
            ]);

            event(new Registered($user));

            Auth::login($user);

            DB::commit();

            return redirect(route('home', absolute: false));

        } catch (\Exception $e) {
            
            DB::rollBack();

            return back()->withErrors(['error' => 'Ocorreu um erro ao registrar sua conta. Por favor, tente novamente.']);
        }
    }
}
