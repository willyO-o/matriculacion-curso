@if ($curso->id)
    <form method="POST" id="formCurso" action="{{ route('curso.update', $curso->id) }}">
        @method('PUT')
    @else
        <form method="POST" id="formCurso" action="{{ route('curso.store') }}">
@endif

<div class="modal-header">
    <h5 class="modal-title font-weight-normal" id="modalEstudianteLabel">{{ $curso->id ? 'Editar' : 'Registrar' }}
        Curso</h5>
    <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    @csrf
    <div class="row">
        <div class="col-md-12">
            <div class="input-group input-group-outline my-3">
                <input type="text" class="form-control" name="titulo" placeholder="titulo"
                    value="{{ $curso->titulo }}">
            </div>
        </div>
        <div class="col-md-12">
            <div class="input-group input-group-outline my-3">
                <textarea name="descripcion" placeholder="Descripcion del curso" id="descripcion" cols="" rows="5"
                    class="form-control">{{ $curso->descripcion }}</textarea>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-outline is-valid my-3">
                <input type="date" class="form-control" name="fecha_inicio" placeholder="Fecha inicio"
                    value="{{ $curso->fecha_inicio?->format('Y-m-d') }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-outline is-valid my-3">
                <input type="date" class="form-control" name="fecha_fin" placeholder="Fecha fin"
                    value="{{ $curso->fecha_fin?->format('Y-m-d') }}">
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <div class="input-group input-group-outline is-valid my-3">
                <input type="number" class="form-control" name="costo" placeholder="Costo" step="0.01"
                    value="{{ $curso->costo }}">
            </div>
        </div>
        <div class="col-md-6">
            <div class="input-group input-group-outline is-invalid my-3">
                <label class="form-label">Estado</label>
                <select name="estado_curso" id="" class="form-select">
                    <option value="BORRADOR" {{ $curso->estado == 'BORRADOR' ? 'selected' : '' }}>BORRADOR</option>
                    <option value="PENDIENTE" {{ $curso->estado == 'PENDIENTE' ? 'selected' : '' }}>PENDIENTE</option>
                    <option value="EN PROCESO" {{ $curso->estado == 'EN PROCESO' ? 'selected' : '' }}>EN PROCESO</option>
                    <option value="FINALIZADO" {{ $curso->estado == 'FINALIZADO' ? 'selected' : '' }}>FINALIZADO</option>
                </select>
            </div>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Cancelar</button>
    <button type="submit" class="btn bg-gradient-primary">Guardar</button>
</div>



</form>
