<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Avance extends Model
{
    protected $table = 'avances';
    protected $fillable = [
        'notas',
        'foto',
        'estado_avance',
        'presupuesto_servicio_id',
    ];

    public function servicio()
    {
        return $this->belongsTo(PresupuestoServicio::class, 'presupuesto_servicio_id');
    }
}
