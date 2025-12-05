<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Curso;
use DataTables;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

require_once base_path('vendor/setasign/fpdf/fpdf.php');

use FPDF;

class CursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {


        if (request()->ajax()) {

            $cursos = Curso::query();

            return DataTables::of($cursos)
                ->addIndexColumn()
                ->editColumn('fecha_inicio', function ($curso) {
                    return $curso->fecha_inicio->format('d/m/Y');
                })
                ->editColumn('fecha_fin', function ($curso) {
                    return $curso->fecha_fin->format('d/m/Y');
                })
                ->editColumn('id', function ($curso) {
                    return '<button class="btn btn-primary btn-sm botonEditar" value="' . route('curso.edit', $curso->id) . '">Editar</button>
                            <button class="btn btn-danger btn-sm botonEliminar" value="' . route('curso.destroy', $curso->id) . '">eliminar</button>
                            <button class="btn btn-info btn-sm botonMatricular" value="' . route('curso.matricular', $curso->id) . '">matricular</button>
                            ';
                })
                ->rawColumns(['id'])
                ->make(true);
        }



        return view('curso.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $html = view('curso.form', ['curso' => new Curso()])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate(Curso::$rules);

        $curso = Curso::create($request->all());

        return response()->json(['mensaje' => 'Curso creado correctamente.']);
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

    public function matricularEstudiante($idCurso)
    {

        $curso = Curso::findOrFail($idCurso);

        $html = view('matriculacion.form', ['curso' => $curso])->render();


        return response()->json(['html' => $html]);
    }

    public function procesarMatricula(Request $request)
    {



        $idCurso = $request->input('id_curso');

        $curso = Curso::findOrFail($idCurso);

        $curso->estudiantes()->attach(
            $request->input('id_estudiante'),
            [
                'nro_matricula' => \App\Models\Matriculacion::generarNroMatricula(),
                'estado_matriculacion' => 'ACTIVO',
                'fecha_matriculacion' => now()
            ]
        );

        return response()->json([
            'mensaje' => 'Estudiante matriculado correctamente.',
            'datos' =>[
                'idCurso' => $idCurso,
                'idEstudiante' => $request->input('id_estudiante')
            ]]
        );
    }


    public function generarPDF($idCurso, $idEstudiante)
    {





        $curso = Curso::findOrFail($idCurso);
        $estudiante = $curso->estudiantes()->where('id_estudiante', $idEstudiante)->first();

        $textoQr = url('verificar-matricula/' . $curso->id . '/' . $estudiante->id);
        $codigoQr = "data:image/png;base64," . base64_encode(QrCode::encoding('UTF-8')->size(400)->format('png')->generate($textoQr));


        #definimos posiciones y dimensiones
        $pdf = new FPDF('P', 'mm', [100, 140]);

        #agregamos una pagina
        $pdf->AddPage();

        $pdf->image(public_path('/assets/fondos/fondo.png'), 0, 0, 100, 70, 'PNG');

        $pdf->image(public_path('/assets/fondos/fondo.png'), 0, 70, 100, 70, 'PNG');

        // definimos la fuente
        $pdf->SetFont('Arial', 'B', 14);
        $pdf->SetXY(28, 32);

        $nombreCompleto = mb_convert_encoding("{$estudiante->nombre} {$estudiante->paterno} {$estudiante->materno}", 'ISO-8859-1', 'UTF-8');

        $pdf->Cell(40, 10, $nombreCompleto);

        $fotoEstudiante = storage_path('app/public/fotos_estudiantes') . '/' . $estudiante->foto;


        $pdf->image($fotoEstudiante, 5, 22, 20, 25, 'png');


        $textoQr = url('verificar-matricula/' . $curso->id . '/' . $estudiante->id);

        $codigoQr = "data:image/png;base64," . base64_encode(QrCode::encoding('UTF-8')->size(400)->format('png')->generate($textoQr));

        $pdf->image($codigoQr, 75, 45, 20, 20, 'png');

        $pdf->Output();

        exit;
    }
}
