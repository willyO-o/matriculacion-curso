<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    protected $table =  'estudiante';

    protected $fillable = [
        'nombre',
        'paterno',
        'materno',
        'ci',
        'fecha_nacimiento',
        'foto',
        'estado'
    ];

    protected $casts = [
        'fecha_nacimiento' => 'date',
    ];


    static $rules = [
        'nombre' => 'required|string|max:150',
        'paterno' => 'required|string|max:150',
        'materno' => 'required|string|max:150',
        'ci' => 'required|string|max:20|unique:estudiante,ci',
        'fecha_nacimiento' => 'required|date',
        'estado'=> 'required|in:ACTIVO,INACTIVO',
        'foto' => 'nullable|image|max:8192|mimes:jpg,png,jpeg,webp'
    ];



}
