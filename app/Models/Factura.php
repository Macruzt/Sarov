<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'numero_factura',
        'fecha_emision',
        'fecha_vencimiento',
        'concepto_detalle',
        'forma_pago',
        'supplier_id',
        'subtotal',
        'total_iva',
        'total',
        'estado',
        'user_id',
    ];

    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_PAGADA = 'pagada';
    const ESTADO_VENCIDA = 'vencida';

    // AGREGAR RELACIÓN CON ITEMS
    public function items()
    {
        return $this->hasMany(FacturaItem::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopePendientes($query)
    {
        return $query->where('estado', self::ESTADO_PENDIENTE);
    }

    public function scopePagadas($query)
    {
        return $query->where('estado', self::ESTADO_PAGADA);
    }

    public function scopeVencidas($query)
    {
        return $query->where('estado', self::ESTADO_VENCIDA)
                    ->orWhere(function($q) {
                        $q->where('estado', self::ESTADO_PENDIENTE)
                          ->where('fecha_vencimiento', '<', now());
                    });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('numero_factura', 'like', "%{$search}%")
                    ->orWhereHas('supplier', function($q) use ($search) {
                        $q->where('nombre_razon_social', 'like', "%{$search}%");
                    });
    }

    public static function getEstados()
    {
        return [
            self::ESTADO_PENDIENTE,
            self::ESTADO_PAGADA,
            self::ESTADO_VENCIDA,
        ];
    }
}