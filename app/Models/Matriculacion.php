<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matriculacion extends Model
{
    protected $table = 'matriculacion';

    protected $fillable = [
        'id_estudiante',
        'id_curso',
        'nro_matricula',
        'estado_matriculacion',
        'fecha_matriculacion'
    ];

    public $casts = [
        'fecha_matriculacion' => 'date',
        'nro_matricula' => 'integer',
    ];



    static function generarNroMatricula()
    {

        $ultimoRegistro = self::latest('id')->first();
        $codigo= '';
        if(!$ultimoRegistro){

            return  $codigo = str_pad(1, 8, '0', STR_PAD_LEFT);

        }

        $ultimoNroMatricula = $ultimoRegistro->nro_matricula;
        $nuevoNroMatricula = $ultimoNroMatricula + 1;
        $codigo = str_pad($nuevoNroMatricula, 8, '0', STR_PAD_LEFT);

        return $codigo;





    }


}
