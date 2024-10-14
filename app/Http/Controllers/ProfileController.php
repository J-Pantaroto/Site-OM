<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function index()
    {
        $totalUsuarios = 20;
        $usuarios = User::orderBy('name', 'ASC')->paginate($totalUsuarios);
        return view('usuarios', compact('usuarios'));
    }

    public function pesquisarUsuarios(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        if($pesquisa===''){
            $usuarios = User::orderBy('name','ASC')->paginate(20);
        }else{
            $usuarios = User::where('name', 'like', "%{$pesquisa}%")->paginate(20);
        }
        

        return response()->json([
            'status' => 'sucesso',
            'quantidade' => $usuarios->count(),
            'usuarios' => $usuarios->items(),
            'links' => $usuarios->links()->render()
        ]); 
    }
    
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();


        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
//metodo para admin excluir usuarios
public function destroyUser($id)
{
    try {
        $profile = User::findOrFail($id);
        $profile->delete();
        return redirect()->route('usuarios')->with('success', 'Usuário excluído com sucesso.');
    } catch (\Exception $e) {
        return redirect()->route('usuarios')->with('error', 'Erro ao excluir o usuário.');
    }
}
}
