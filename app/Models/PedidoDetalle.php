<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PedidoDetalle extends Model
{
    protected $fillable = [
        'pedido_id',
        'precio',
        'observaciones',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class);
    }

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(
            Producto::class,
            'pedido_detalle_producto',
            'pedido_detalle_id',
            'producto_id'
        )->withTimestamps();
    }

    public function componentesMixto(): BelongsToMany
    {
        return $this->belongsToMany(
            ComponenteMixto::class,
            'pedido_detalle_componentes',
            'pedido_detalle_id',
            'componente_mixto_id'
        )->withTimestamps();
    }
}