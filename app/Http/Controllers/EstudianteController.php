<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Estudiante;

use DataTables;

class EstudianteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        if (request()->ajax()) {
            $estudiantes = Estudiante::query();
            return DataTables::of($estudiantes)
            ->make(true);
        }

        //
        return view('estudiante.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $html = view('estudiante.form')->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Estudiante::$rules);

        $fotografia = $request->file('foto');

        $nombreFoto =  date('YmdHis') . '_' . uniqid() . '.' . $fotografia->getClientOriginalExtension();

        if (!file_exists(storage_path('app/public/fotos_estudiantes'))) {
            mkdir(storage_path('app/public/fotos_estudiantes'), 0755, true);
        }

        $fotografia->move(storage_path('app/public/fotos_estudiantes'), $nombreFoto);

        $datos = $request->all();
        $datos['foto'] = $nombreFoto;

        $estudiante = Estudiante::create($datos);



        return $estudiante;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
