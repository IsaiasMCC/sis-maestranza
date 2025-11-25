<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PresupuestoServicio extends Model
{
    protected $table = 'presupuesto_servicios';
    protected $fillable = [
        'detalles',
        'precio',
        'servicio_id',
        'reserva_id',
        'trabajador_id',
    ];

    public function servicio()
    {
        return $this->belongsTo(Servicio::class, 'servicio_id');
    }


    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(User::class, 'trabajador_id');
    }

    public function avances()
    {
        return $this->hasMany(Avance::class);
    }
}
