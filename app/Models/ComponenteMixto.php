<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ComponenteMixto extends Model
{
    protected $table = 'componentes_mixtos';

    protected $fillable = [
        'nombre',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function pedidoDetalles(): BelongsToMany
    {
        return $this->belongsToMany(
            PedidoDetalle::class,
            'pedido_detalle_componentes'
        )->withTimestamps();
    }
}