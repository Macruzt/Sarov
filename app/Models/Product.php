<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'reference', 
        'description', 
        'unit', 
        'current_stock', 
        'minimum_stock', 
        'unit_price', 
        'iva_percentage', 
        'category', 
        'status'
    ];

    protected $casts = [
        'current_stock' => 'decimal:2',
        'minimum_stock' => 'decimal:2',
        'unit_price' => 'decimal:2',
        'iva_percentage' => 'decimal:2',
    ];

    // Relación: Un producto tiene muchos items de facturas
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    // Relación: Un producto tiene muchos movimientos de inventario
    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Scope para productos activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope para productos con stock bajo
    public function scopeLowStock($query)
    {
        return $query->whereColumn('current_stock', '<=', 'minimum_stock');
    }

    // Calcular precio con IVA
    public function getPriceWithIvaAttribute()
    {
        return $this->unit_price * (1 + ($this->iva_percentage / 100));
    }

    // Método para actualizar stock
    public function updateStock($quantity, $type = 'entry', $reference_type = 'manual', $reference_id = null, $user_id = null)
    {
        $previous_stock = $this->current_stock;
        
        if ($type === 'entry') {
            $this->current_stock += $quantity;
        } else {
            $this->current_stock -= $quantity;
        }
        
        $this->save();

        // Registrar movimiento
        InventoryMovement::create([
            'product_id' => $this->id,
            'type' => $type,
            'quantity' => $quantity,
            'previous_stock' => $previous_stock,
            'new_stock' => $this->current_stock,
            'reference_type' => $reference_type,
            'reference_id' => $reference_id,
            'created_by' => $user_id ?? auth()->id()
        ]);

        return $this;
    }
}