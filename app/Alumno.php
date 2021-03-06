<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Alumno extends Model
{
     protected $fillable = [
        'carnet','nombre','apellido','direccion','telefono','correo','lugar_trabajo','telefono_trabajo', 'ingresadoPor', 'modificadoPor',
    ];


    public function alumno_escuela()
    {
        return $this->hasMany('App\Alumno_escuela','carnet','carnet');
    }

    protected $primaryKey = 'carnet';
    public $incrementing = false;

}
