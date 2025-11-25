<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{

    protected $table = 'reservas';
    protected $fillable = [
        'fecha',
        'hora',
        'detalles',
        'total',
        'estado_reserva',
        'cliente_id',
    ];


    public function cliente()
    {
        return $this->belongsTo(User::class, 'cliente_id');
    }

    public function detalles()
    {
        return $this->hasMany(PresupuestoServicio::class, 'reserva_id');
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
