<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
    protected $table = 'mensajes';
    protected $fillable = [
        'mensaje',
        'empleado_id',
        'ticket_id',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function empleado()
    {
        return $this->belongsTo(User::class, 'empleado_id');
    }
}
