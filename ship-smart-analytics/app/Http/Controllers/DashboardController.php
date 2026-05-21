<?php

namespace App\Http\Controllers;

use App\Models\Pacote;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total'    => Pacote::count(),
            'pendente' => Pacote::pendente()->count(),
            'em_rota'  => Pacote::emRota()->count(),
            'entregue' => Pacote::entregue()->count(),
        ];

        $recentes = Pacote::latest()->take(5)->get();

        return view('dashboard', compact('stats', 'recentes'));
    }
}