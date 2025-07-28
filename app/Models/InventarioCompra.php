<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventarioCompra extends Model
{
    protected $table = 'inventario_compras';
    
    protected $fillable = [
        'codigo_producto',
        'marca',
        'referencia',
        'detalle_producto',
        'unidad_medida',
        'stock_actual',
        'stock_minimo',
        'ubicacion',
        'estado',
        'numero_factura',
        'fecha_factura',
        'proveedor',
        'fecha_vencimiento',
        'metodo_pago',
        'cantidad_comprada',
        'valor_unitario',
        'iva_porcentaje',
        'valor_iva',
        'total_compra'
    ];

    protected $casts = [
        'fecha_factura' => 'date',
        'fecha_vencimiento' => 'date',
        'valor_unitario' => 'decimal:2',
        'valor_iva' => 'decimal:2',
        'total_compra' => 'decimal:2'
    ];

    // Relación con salidas
    public function salidas()
    {
        return $this->hasMany(InventarioSalida::class, 'inventario_compra_id');
    }

    // Scope para stock bajo
    public function scopeStockBajo($query)
    {
        return $query->whereRaw('stock_actual <= stock_minimo');
    }
}