<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    use HasFactory;

    protected $table = 'stock_movements';

    protected $fillable = [
        'product_id',
        'movement_type',
        'quantity',
        'reference_type',
        'reference_id',
        'description',
        'movement_date'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'reference_id' => 'integer',
        'movement_date' => 'date'
    ];

    // Relación con producto
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Scope para entradas
    public function scopeEntradas($query)
    {
        return $query->where('movement_type', 'entrada');
    }

    // Scope para salidas
    public function scopeSalidas($query)
    {
        return $query->where('movement_type', 'salida');
    }
}