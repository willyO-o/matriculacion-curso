<?php

use App\Http\Controllers\CursoController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EstudianteController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes();



Route::group(['prefix' => '', 'middleware' => 'auth'], function () {

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

    Route::resource('estudiante', EstudianteController::class);

    Route::get('estudiante-buscar', [EstudianteController::class, 'buscarEstudiantes'])->name('estudiante.buscar');


    Route::resource('curso', CursoController::class);

    Route::get('curso-matricular/{id}', [CursoController::class, 'matricularEstudiante'])->name('curso.matricular');
    Route::post('curso-store-matricular', [CursoController::class, 'procesarMatricula'])->name('curso.store-matricular');


    Route::get('matricula-pdf/{idCurso}/{idEstudiante}', [CursoController::class, 'generarPDF'])->name('matricula.pdf');

    //
});
