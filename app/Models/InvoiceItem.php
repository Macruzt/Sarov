<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 
        'product_id', 
        'quantity', 
        'unit_price', 
        'iva_percentage', 
        'iva_amount', 
        'total_amount', 
        'order_id'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'iva_percentage' => 'decimal:2',
        'iva_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relación: Un item pertenece a una factura
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    // Relación: Un item pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación: Un item puede pertenecer a una orden de servicio
    public function order()
    {
        return $this->belongsTo(\App\Models\orders::class, 'order_id');
    }

    // Eventos del modelo para calcular automáticamente los montos
    protected static function boot()
    {
        parent::boot();

        // Antes de guardar, calcular los montos automáticamente
        static::saving(function ($item) {
            $subtotal = $item->quantity * $item->unit_price;
            $item->iva_amount = $subtotal * ($item->iva_percentage / 100);
            $item->total_amount = $subtotal + $item->iva_amount;
        });

        // Después de guardar, recalcular totales de la factura
        static::saved(function ($item) {
            if ($item->invoice) {
                $item->invoice->calculateTotals();
            }
        });

        // Después de eliminar, recalcular totales de la factura
        static::deleted(function ($item) {
            if ($item->invoice) {
                $item->invoice->calculateTotals();
            }
        });
    }
}