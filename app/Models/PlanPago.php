<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanPago extends Model
{
    protected $table = 'plan_pagos';
    protected $fillable = [
        'saldo',
        'tipo_pago',
        'total',
        'reserva_id',
    ];

    public function reserva()
    {
        return  $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function detalle_pagos()
    {
        return $this->hasMany(DetallePago::class);
    }
}
