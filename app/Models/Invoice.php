<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number', 
        'supplier_id', 
        'invoice_date', 
        'due_date', 
        'subtotal', 
        'iva_amount', 
        'total_amount', 
        'notes', 
        'status', 
        'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'iva_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    // Relación: Una factura pertenece a un proveedor
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relación: Una factura tiene muchos items
    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Relación: Una factura fue creada por un usuario
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Scope para facturas pendientes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope para facturas recibidas
    public function scopeReceived($query)
    {
        return $query->where('status', 'received');
    }

    // Calcular totales automáticamente
    public function calculateTotals()
    {
        $subtotal = $this->items->sum(function ($item) {
            return $item->quantity * $item->unit_price;
        });

        $iva_amount = $this->items->sum('iva_amount');
        $total_amount = $subtotal + $iva_amount;

        $this->update([
            'subtotal' => $subtotal,
            'iva_amount' => $iva_amount,
            'total_amount' => $total_amount
        ]);
    }

    // Confirmar recepción de factura (actualizar inventario)
    public function confirmReception()
    {
        if ($this->status !== 'pending') {
            return false;
        }

        foreach ($this->items as $item) {
            $item->product->updateStock(
                $item->quantity, 
                'entry', 
                'invoice', 
                $this->id, 
                auth()->id()
            );
        }

        $this->update(['status' => 'received']);
        return true;
    }
}