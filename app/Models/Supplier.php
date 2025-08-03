<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'business_name', 
        'nit', 
        'address', 
        'phone', 
        'email', 
        'contact_person', 
        'status'
    ];

    // Relación: Un proveedor tiene muchas facturas
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // Scope para proveedores activos
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope para proveedores inactivos
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }
}