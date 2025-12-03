    <form method="POST" id="formMatricular" action="{{ route('curso.store-matricular') }}">

        <input type="hidden" name="id_curso" value="{{ $curso->id }}">
        <div class="modal-header">
            <h5 class="modal-title font-weight-normal" id="modalEstudianteLabel">
                Matricular Estudiante
            </h5>
            <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            @csrf
            <div class="row">
                <div class="col-md-12">

                    <h2>{{ $curso->titulo }}</h2>

                </div>

                <div class="col-md-12">

                    <div class="form-group">
                        <label for="">Buscar Estudiante </label>

                        <select name="id_estudiante" id="selectEstudiante" class="form-control" style="width: 100%">


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
