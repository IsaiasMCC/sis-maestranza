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
}
