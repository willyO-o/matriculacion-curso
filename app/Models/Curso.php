<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

use App\Models\Estudiante;
use App\Models\Matriculacion;

class Curso extends Model
{
    protected $table = 'curso';

    protected $fillable = [
        'codigo',
        'titulo',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
        'costo',
        'estado_curso'
    ];


    static $rules = [
        'titulo' => 'required|string|max:255',
        'descripcion' => 'nullable|string',
        'fecha_inicio' => 'required|date|before_or_equal:fecha_fin',
        'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        'costo' => 'required|numeric|min:0|max:1000',
        'estado_curso' => 'required|in:BORRADOR,PENDIENTE,EN PROCESO,FINALIZADO'
    ];

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
        'costo' => 'decimal:2',
    ];

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'matriculacion',
            'id_curso',
            'id_estudiante',
            )
            ->withPivot('nro_matricula','estado_matriculacion','fecha_matriculacion');
            // ->using(Matriculacion::class);

    }


    # se ejecuta antes de crear un nuevo curso
    public static function boot()
    {
        parent::boot();
        static::creating(function($curso)  {

            $curso->codigo =self::generarCodigoCurso();
        });
    }


    // recomendacion: este deberia de ir en un helper o archivo de utilidades separado

    public static function generarCodigoCurso()
    {

        $codigo = "PSG-" . Str::random(5);

        if(Curso::where('codigo', $codigo)->exists()){
            return self::generarCodigoCurso();
        }

        return $codigo;
    }
}
