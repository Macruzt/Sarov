<?php
// app/Models/FacturaItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacturaItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'factura_id',
        'product_id',
        'detalle',
        'unidad_medida',
        'cantidad',
        'valor_unitario',
        'porcentaje_iva',
    ];

    protected $appends = [
        'valor_iva_unitario',
        'valor_con_iva_unitario',
        'total_item'
    ];

    public function factura()
    {
        return $this->belongsTo(Factura::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Atributos calculados
    public function getValorIvaUnitarioAttribute()
    {
        return $this->valor_unitario * ($this->porcentaje_iva / 100);
    }

    public function getValorConIvaUnitarioAttribute()
    {
        return $this->valor_unitario + $this->getValorIvaUnitarioAttribute();
    }

    public function getTotalItemAttribute()
    {
        return $this->getValorConIvaUnitarioAttribute() * $this->cantidad;
    }
}