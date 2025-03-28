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
        $usuarios = User::where('supervisor',false)
        ->orderBy('name', 'ASC')
        ->paginate($totalUsuarios);
        return view('usuarios', compact('usuarios'));
    }

    public function pesquisarUsuarios(Request $request)
    {
        $pesquisa = $request->input('pesquisa');
        if ($pesquisa === '' || $pesquisa === null) {
            $usuarios = User::where('supervisor',false)
            ->orderBy('name', 'ASC')
            ->paginate(20);
        } else {
            $usuarios = User::where('supervisor',false)
            ->where('name', 'like', "%{$pesquisa}%")
            ->paginate(20);
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

    public function update(ProfileUpdateRequest $request)
    {
        $user = Auth::user();
        $request->merge([
            'celular' => preg_replace('/\D/', '', $request->input('celular')), // Remove pontuação
        ]);
        $validatedData = $request->validate([
            'name' => 'required|string|max:150',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'celular' => 'required|digits_between:11,12',
            'state' => 'required|string|exists:states,abbreviation',
            'city' => 'required|string|exists:cities,ibge_code',
            'address' => 'required|string|max:255',
            'house_number' => 'required|string|max:10',
            'neighborhood' => 'required|string|max:255',
            'zip_code' => 'required|string|max:9|regex:/^\d{5}-\d{3}$/',
            'complement' => 'nullable|string|max:255',
        ]);

        $state = State::where('abbreviation', $validatedData['state'])->first();
        if (!$state) {
            return response()->json(['error' => 'Estado inválido.'], 422);
        }

        $city = City::where('ibge_code', $validatedData['city'])
            ->where('state_id', $state->id)
            ->first();
        if (!$city) {
            return response()->json(['error' => 'Cidade inválida para o estado selecionado.'], 422);
        }

        /** @var User $user */
        $user->update([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'state_id' => $state->id,
            'city_id' => $city->id,
            'address' => $validatedData['address'],
            'celular' => $validatedData['celular'],
            'house_number' => $validatedData['house_number'],
            'neighborhood' => $validatedData['neighborhood'],
            'zip_code' => $validatedData['zip_code'],
            'complement' => $validatedData['complement'] ?? null,
            'address_complete' => true,
        ]);

        return response()->json(['message' => 'Perfil atualizado com sucesso!'], 200);
    }

    public function updateRole(Request $request, User $usuario)
    {
        $data = $request->validate([
            'admin' => 'boolean',
            'supervisor' => 'boolean',
        ]);
        $usuario->update([
            'admin' => $request->has('admin'),
            'supervisor' => $request->has('supervisor'),
        ]);

        return redirect()->route('usuarios')->with('success', 'Permissões do usuário atualizadas com sucesso!');
    }
    public function editRole(User $usuario)
    {
        if (!$usuario) {
            return redirect()->route('profile.index')->withErrors(['error' => 'Usuário não encontrado.']);
        }
        return view('profile.definiradm', compact('usuario'));
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
