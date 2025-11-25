<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    protected $table = 'tickets';
    protected $fillable = [
        'nro',
        'detalles',
        'estado',
        'reserva_id',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class, 'reserva_id');
    }

    public function mensajes()
    {
        return $this->hasMany(Mensaje::class);
    }
}
