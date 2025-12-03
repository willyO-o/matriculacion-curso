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
                ->addIndexColumn()
                ->editColumn('nombre', function ($estudiante) {
                    $nombreCompleto = "{$estudiante->nombre} {$estudiante->paterno} {$estudiante->materno}";
                    return $nombreCompleto;
                })
                ->editColumn('foto', function ($estudiante) {
                    $urlImagen = url('storage/fotos_estudiantes/' . $estudiante->foto);
                    return '<img src="' . $urlImagen . '" width="75" height="75">';
                })
                ->editColumn('fecha_nacimiento', function ($estudiante) {

                    return $estudiante->fecha_nacimiento->age . ' años';
                })
                ->editColumn('estado', function ($estudiante) {
                    $tipo = $estudiante->estado == 'ACTIVO' ? 'btn-success' : 'btn-danger';
                    return '<button type="button" class="botonEstado btn btn-sm ' . $tipo . '"  value="' . route('estudiante.update', $estudiante->id) . '" > ' . $estudiante->estado . '</button>';
                })
                ->editColumn('id', function ($estudiante) {
                    return '<button class="btn btn-primary btn-sm botonEditar" value="' . route('estudiante.edit', $estudiante->id) . '">Editar</button>
                            <button class="btn btn-danger btn-sm botonEliminar" value="' . route('estudiante.destroy', $estudiante->id) . '">eliminar</button>';
                })
                ->rawColumns(['foto', 'estado', 'id'])
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
        $html = view('estudiante.form', ['estudiante' => new Estudiante()])->render();

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


        return response()->json(['mensaje' => 'Estudiante creado correctamente.']);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        $html = view('estudiante.form', compact('estudiante'))->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {

        $estudiante = Estudiante::findOrFail($id);

        if ($request->isMethod('patch')) {
            $estudiante->estado = $request->estado;
            $estudiante->save();
            return response()->json(['mensaje' => 'Estado actualizado correctamente.']);
        }


        # en caso de que sea metodo PUT se ejecuta este bloque

        $rules = Estudiante::$rules;
        $rules['ci'] =  'required|string|max:20|unique:estudiante,ci,' . $id;

        $request->validate($rules);

        $imagenAnterior = $estudiante->foto;

        $estudiante->fill($request->all());

        if ($request->file('foto')) {
            $fotografia = $request->file('foto');
            $nombreFoto =  date('YmdHis') . '_' . uniqid() . '.' . $fotografia->getClientOriginalExtension();
            if (!file_exists(storage_path('app/public/fotos_estudiantes'))) {
                mkdir(storage_path('app/public/fotos_estudiantes'), 0755, true);
            }


            $fotografia->move(storage_path('app/public/fotos_estudiantes'), $nombreFoto);
            $estudiante->foto = $nombreFoto;

            if (file_exists(storage_path('app/public/fotos_estudiantes/' . $imagenAnterior))) {
                unlink(storage_path('app/public/fotos_estudiantes/' . $imagenAnterior));
            }
        }


        $estudiante->save();

        return response()->json(['mensaje' => 'Estudiante actualizado correctamente.']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $estudiante = Estudiante::findOrFail($id);

        if (file_exists(storage_path('app/public/fotos_estudiantes/' . $estudiante->foto))) {
            unlink(storage_path('app/public/fotos_estudiantes/' . $estudiante->foto));
        }

        $estudiante->delete();


        return response()->json(['mensaje' => 'Estudiante eliminado correctamente.']);
    }

    public function buscarEstudiantes(Request $request)
    {

        $termino = $request->get('term','');

        $termino = '%' . $termino . '%';


        $estudiantes = Estudiante::selectRaw("id, CONCAT(nombre,' ',paterno,' ',materno,' - ',ci) AS text")
        ->where('nombre', 'LIKE',$termino)
        ->orWhere('paterno', 'LIKE',$termino)
        ->orWhere('materno', 'LIKE',$termino)
        ->orWhere('ci', 'LIKE',$termino)
        ->orWhereRaw("CONCAT(nombre,' ',paterno,' ',materno) LIKE ?", [$termino])
        ->orWhereRaw("CONCAT(paterno,' ',materno,' ',nombre) LIKE ?", [$termino])
        ->orWhereRaw("CONCAT(paterno,' ',materno,' ',nombre) LIKE ?", [$termino])
        ->limit(10)
        ->get();

        return response()->json(['results' => $estudiantes]);

    }
}
