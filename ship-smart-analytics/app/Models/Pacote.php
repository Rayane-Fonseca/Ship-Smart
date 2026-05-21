<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pacote extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'nome',
        'codigo',
        'fabricante_fornecedor',
        'preco',
        'quantidade',
        'destinatario',
        'peso',
        'status',
    ];

    protected $casts = [
        'preco'      => 'decimal:2',
        'peso'       => 'decimal:3',
        'quantidade' => 'integer',
    ];

    // Regra de Negócio: Peso mínimo (0.01 kg)
    public function isPesoValido(): bool
    {
        return $this->peso >= 0.01;
    }

    // Scopes de filtro por status
    public function scopePendente($query)
    {
        return $query->where('status', 'Pendente');
    }

    public function scopeEmRota($query)
    {
        return $query->where('status', 'Em Rota');
    }

    public function scopeEntregue($query)
    {
        return $query->where('status', 'Entregue');
    }
}