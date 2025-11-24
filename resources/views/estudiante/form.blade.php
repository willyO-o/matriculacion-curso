<form  method="POST" id="formEstudiante"  action="{{ route('estudiante.store') }}">
    <div class="modal-header">
        <h5 class="modal-title font-weight-normal" id="modalEstudianteLabel">Registrar Estudiante</h5>
        <button type="button" class="btn-close text-dark" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <input type="text" class="form-control" name="nombre" placeholder="Nombre">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <input type="text" class="form-control" name="paterno" placeholder="Apellido Paterno">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <input type="text" class="form-control" name="materno" placeholder="Apellido Materno">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline my-3">
                    <input type="text" class="form-control" name="ci" placeholder="Nro C.I.">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline is-valid my-3">
                    <input type="date" class="form-control" name="fecha_nacimiento" placeholder="Fecha nacimiento">
                </div>
            </div>
            <div class="col-md-6">
                <div class="input-group input-group-outline is-invalid my-3">
                    <label class="form-label">Estado</label>
                    <select name="estado" id="" class="form-select">
                        <option value="ACTIVO">ACTIVO</option>
                        <option value="INACTIVO">INACTIVO</option>
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="input-group input-group-outline is-invalid my-3">
                    <input type="file" class="" name="foto"
                        accept="image/jpg, image/png, image,jpeg, image/webp">
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn bg-gradient-secondary" data-bs-dismiss="modal">Close</button>
        <button type="submit" class="btn bg-gradient-primary">Guardar</button>
    </div>



</form>
