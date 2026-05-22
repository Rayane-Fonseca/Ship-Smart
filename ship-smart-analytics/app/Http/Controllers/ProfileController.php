<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = auth()->user();
    
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'cargo' => ['nullable', 'string', 'max:255'],
            'perfil' => ['nullable', 'string', 'max:255'],
            'unidade' => ['nullable', 'string', 'max:255'],
            'turno' => ['nullable', 'string', 'max:255'],
            'descricao' => ['nullable', 'string', 'max:1000'],
            'resumo_operacional' => ['nullable', 'string', 'max:1000'],
            'tag_1' => ['nullable', 'string', 'max:50'],
            'tag_2' => ['nullable', 'string', 'max:50'],
            'tag_3' => ['nullable', 'string', 'max:50'],
            'tag_4' => ['nullable', 'string', 'max:50'],
        ]);

        

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Perfil atualizado com sucesso.');
    }
}