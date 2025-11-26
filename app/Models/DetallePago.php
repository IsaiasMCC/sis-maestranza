<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallePago extends Model
{
    protected $table = 'detalle_pagos';
    protected $fillable = [
        'fecha',
        'comprobante',
        'monto',
        'saldo',
        'plan_pago_id',
        'metodo_pago_id',

    ];

    public function plan_pago()
    {
        return $this->belongsTo(PlanPago::class);
    }

    public function metodo_pago()
    {
        return $this->belongsTo(MetodoPago::class);
    }
}
