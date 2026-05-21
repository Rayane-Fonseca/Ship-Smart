<?php

namespace App\Http\Controllers;

use App\Models\Pacote;
use Illuminate\Http\Request;

class PacoteController extends Controller
{
    public function index(Request $request)
    {
        $query = Pacote::query();

        // Filtro por status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Busca por código ou destinatário
        if ($request->filled('busca')) {
            $query->where(function ($q) use ($request) {
                $q->where('codigo', 'like', '%' . $request->busca . '%')
                  ->orWhere('destinatario', 'like', '%' . $request->busca . '%')
                  ->orWhere('nome', 'like', '%' . $request->busca . '%');
            });
        }

        $pacotes = $query->latest()->paginate(10);

        return view('pacotes.index', compact('pacotes'));
    }

    public function create()
    {
        return view('pacotes.create');
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'required|string|max:100|unique:pacotes',
            'fabricante_fornecedor'=> 'required|string|max:255',
            'preco'                => 'required|numeric|min:0',
            'quantidade'           => 'required|integer|min:1',
            'destinatario'         => 'required|string|max:255',
            'peso'                 => 'required|numeric|min:0.01',
            'status'               => 'required|in:Pendente,Em Rota,Entregue',
        ], [
            'codigo.unique'        => 'Este código de rastreio já está cadastrado.',
            'peso.min'             => 'O peso mínimo é 0,01 kg.',
            'quantidade.min'       => 'A quantidade deve ser pelo menos 1.',
        ]);

        Pacote::create($dados);

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote cadastrado com sucesso!');
    }

    public function show(Pacote $pacote)
    {
        return view('pacotes.show', compact('pacote'));
    }

    public function edit(Pacote $pacote)
    {
        return view('pacotes.edit', compact('pacote'));
    }

    public function update(Request $request, Pacote $pacote)
    {
        $dados = $request->validate([
            'nome'                 => 'required|string|max:255',
            'codigo'               => 'required|string|max:100|unique:pacotes,codigo,' . $pacote->id,
            'fabricante_fornecedor'=> 'required|string|max:255',
            'preco'                => 'required|numeric|min:0',
            'quantidade'           => 'required|integer|min:1',
            'destinatario'         => 'required|string|max:255',
            'peso'                 => 'required|numeric|min:0.01',
            'status'               => 'required|in:Pendente,Em Rota,Entregue',
        ]);

        $pacote->update($dados);

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote atualizado com sucesso!');
    }

    public function destroy(Pacote $pacote)
    {
        $pacote->delete();

        return redirect()->route('pacotes.index')
                         ->with('sucesso', 'Pacote removido com sucesso!');
    }
}