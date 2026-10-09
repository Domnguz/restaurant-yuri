<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Producto extends Model
{
    protected $appends = ['personalizable', 'precios_personalizacion'];

    public function getPersonalizableAttribute(): bool
    {
        return $this->precio === null || preg_match('/^mixto(?:\s|$)/iu', trim($this->nombre)) === 1;
    }

    public function getPreciosPersonalizacionAttribute(): array
    {
        if (! $this->personalizable) {
            return [];
        }

        return (float) $this->precio > 0 ? [(float) $this->precio] : [7, 8, 9, 10, 11, 12];
    }

    protected $fillable = [
        'categoria_id',
        'nombre',
        'precio',
        'activo',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'activo' => 'boolean',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function pedidoDetalles(): BelongsToMany
    {
        return $this->belongsToMany(
            PedidoDetalle::class,
            'pedido_detalle_producto'
        )->withPivot('precio')->withTimestamps();
    }
}
