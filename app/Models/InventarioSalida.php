<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioSalida extends Model
{
    protected $table = 'inventario_salidas';
    
    protected $fillable = [
        'inventario_compra_id',
        'os_id',
        'tipo_orden',
        'cantidad_utilizada',
        'area_trabajo',
        'fecha_salida',
        'quien_recibe',
        'observaciones',
        'costo_unitario',
        'costo_total'
    ];

    protected $casts = [
        'fecha_salida' => 'date',
        'costo_unitario' => 'decimal:2',
        'costo_total' => 'decimal:2'
    ];

    // Relación con inventario compras
    public function inventarioCompra()
    {
        return $this->belongsTo(InventarioCompra::class, 'inventario_compra_id');
    }
}