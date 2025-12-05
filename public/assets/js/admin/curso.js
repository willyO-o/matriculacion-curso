
$(function () {
    'use strict';

    let modalCurso = new bootstrap.Modal(document.querySelector('#modalCurso'));


    let tablaCursos = $('#tablaCursos').DataTable({
        serverSide: true,
        processing: true,
        ajax: '/curso',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'codigo', name: 'codigo' },
            { data: 'titulo', name: 'titulo' },
            { data: 'fecha_inicio', name: 'fecha_inicio' },
            { data: 'fecha_fin', name: 'fecha_fin' },
            { data: 'estado_curso', name: 'estado_curso' },
            { data: 'id', name: 'id' },
        ]
    })
        .on('click', '.botonEliminar', function (evento) {
            evento.preventDefault();

        })
        .on('click', '.botonMatricular', function (evento) {
            evento.preventDefault();

            const urlProcesar = $(this).val()
            modalCurso.show();

            $.get(urlProcesar)
                .done(function (data) {

                    $('#modalCurso .modal-content').html(data.html);

                    // incializamos el select2 para el select de estudiantes
                    $('#selectEstudiante').select2({
                        dropdownParent: $('#modalCurso .modal-content'),
                        minimumInputLength: 2,
                        ajax: {
                            url: '/estudiante-buscar',


                            data: function (params) {

                                console.log("parametros enviados al servidor: ", params);

                                var query = {
                                    search: params.term,
                                    type: 'public'
                                }

                                // Query parameters will be ?search=[term]&type=public
                                return query;
                            }
                        }
                    });

                })
        })




    $('#btnRegistrar').on('click', function () {

        modalCurso.show();

        $.get('/curso/create')
            .done(function (data) {

                $('#modalCurso .modal-content').html(data.html);
            })

    })

    $(document)
        .on('submit', '#formCurso', function (evento) {
            evento.preventDefault();

            let formulario = $(this);

            let datos = formulario.serializeArray();

            let urlProcesar = formulario.attr('action');

            $.post(urlProcesar, datos)
                .done(function (respuesta) {

                    tablaCursos.ajax.reload();

                    modalCurso.hide();

                    Swal.fire('Éxito', respuesta.mensaje, 'success');

                })


        })
        .on('submit', '#formMatricular',function(evento){
            evento.preventDefault();

            let formulario = $(this);

            let datos = formulario.serializeArray();

            $.post(formulario.attr('action'), datos)
                .done(function (respuesta) {

                     modalCurso.hide();
                     Swal.fire('Éxito', respuesta.mensaje, 'success');

                    let urlPdf =  window.location.origin + '/matricula-pdf/' + respuesta.datos.idCurso + '/' + respuesta.datos.idEstudiante;

                    window.open(urlPdf, '_blank', 'width=800,height=600');



                })

        })

})
