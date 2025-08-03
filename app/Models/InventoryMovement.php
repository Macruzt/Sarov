<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id', 
        'type', 
        'quantity', 
        'previous_stock', 
        'new_stock', 
        'reference_type', 
        'reference_id', 
        'notes', 
        'created_by'
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'previous_stock' => 'decimal:2',
        'new_stock' => 'decimal:2',
    ];

    // Relación: Un movimiento pertenece a un producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relación: Un movimiento fue creado por un usuario
    public function createdBy()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by');
    }

    // Scope para movimientos de entrada
    public function scopeEntries($query)
    {
        return $query->where('type', 'entry');
    }

    // Scope para movimientos de salida
    public function scopeExits($query)
    {
        return $query->where('type', 'exit');
    }

    // Scope para movimientos de un producto específico
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // Obtener la referencia relacionada (factura, orden, etc.)
    public function getReference()
    {
        switch ($this->reference_type) {
            case 'invoice':
                return Invoice::find($this->reference_id);
            case 'order':
                return \App\Models\orders::find($this->reference_id);
            default:
                return null;
        }
    }

    // Obtener el nombre descriptivo del tipo de referencia
    public function getReferenceTypeNameAttribute()
    {
        $types = [
            'invoice' => 'Factura',
            'order' => 'Orden de Servicio',
            'manual' => 'Manual',
            'adjustment' => 'Ajuste',
            'correction' => 'Corrección'
        ];

        return $types[$this->reference_type] ?? $this->reference_type;
    }

    // Obtener el texto descriptivo del movimiento
    public function getDescriptionAttribute()
    {
        $type = $this->type === 'entry' ? 'Entrada' : 'Salida';
        $reference = $this->reference_type_name;
        
        if ($this->reference_id) {
            return "{$type} por {$reference} #{$this->reference_id}";
        }
        
        return "{$type} {$reference}";
    }
}