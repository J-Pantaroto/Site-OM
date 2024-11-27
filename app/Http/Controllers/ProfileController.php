<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\State;
use App\Models\City;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function index()
    {
        $totalUsuarios = 20;
        $usuarios = User::orderBy('name', 'ASC')->paginate($totalUsuarios);
        return view('usuarios', compact('usuarios'));
    }

    public function pesquisarUsuarios(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        if ($pesquisa === '' || $pesquisa === null) {
            $usuarios = User::orderBy('name', 'ASC')->paginate(20);
        } else {
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
    { {
            $user = Auth::user();

            $states = State::all();


            $cities = $user->state_id
                ? City::where('state_id', $user->state_id)->get()
                : collect();

            return view('profile.edit', compact('user', 'states', 'cities'));
        }
    }


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $request->validate([
            'state' => 'required|exists:states,id',
            'city' => 'required|exists:cities,id',
        ]);

        /** @var User $user */
        $user->update([
            'state_id' => $request->state,
            'city_id' => $request->city,
        ]);

        return redirect()->route('profile.edit')->with('success', 'Perfil atualizado com sucesso!');
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
        $user = User::findOrFail($id);

        if ($user->isAdmin()) {
            return redirect()->route('usuarios')->with('error', 'Você não pode excluir um administrador.');
        }
        $user->vendas()->delete();
        $user->delete();

        return redirect()->route('usuarios')->with('success', 'Usuário excluído com sucesso.');
    }
}
