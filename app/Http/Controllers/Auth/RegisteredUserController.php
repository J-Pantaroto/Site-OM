<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use App\Rules\CnpjCpf;
use Illuminate\View\View;
use App\Services\CnpjCpfService;

class RegisteredUserController extends Controller
{
    protected $cnpjCpfService;

    public function __construct(CnpjCpfService $cnpjCpfService)
    {
        $this->cnpjCpfService = $cnpjCpfService;
    }
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
        $request->merge([
            'cpf_cnpj' => preg_replace('/\D/', '', $request->cpf_cnpj)
        ]);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'cpf_cnpj' => ['required', 'string', 'min:11', 'max:14', 'unique:' . User::class, new CnpjCpf()],
        ]);
        if (strlen($request->cpf_cnpj) === 14) {
            $cnpjData = $this->cnpjCpfService->verificarCnpj($request->cpf_cnpj);

            if (!$cnpjData || isset($cnpjData['message']) && $cnpjData['message'] === 'CNPJ inválido ou inativo.') {
                return redirect()->back()->withErrors(['cpf_cnpj' => 'CNPJ inválido ou inativo.']);
            }
        }
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'cpf_cnpj' => $request->cpf_cnpj,
        ]);
        Session::put('email', $request->email);
        event(new Registered($user));
        Auth::login($user);
        $request->session()->regenerate();
        setcookie('carrinho', json_encode([]), time() + 86400, '/');
        return redirect(route('verification.notice'))->with('email', $user->email); // Adiciona o email à sessão;

    }
}
