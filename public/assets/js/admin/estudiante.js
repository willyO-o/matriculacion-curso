$(function () {

    'use strict';

    let modaEstudiante = new bootstrap.Modal(document.querySelector('#modalEstudiante'));


    let tablaEstudiantes = $('#tablaEstudiantes').DataTable({
        language:{
            url: 'https://cdn.datatables.net/plug-ins/2.3.5/i18n/es-ES.json'
        },
        processing: true,
        serverSide: true,
        ajax: '/estudiante',
        columns: [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'foto', name: 'foto' },
            { data: 'nombre', name: 'nombre' },
            { data: 'ci', name: 'ci' },
            { data: 'fecha_nacimiento', name: 'fecha_nacimiento' },
            { data: 'estado', name: 'estado' },
            { data: 'id', name: 'id', orderable: false, searchable: false },
        ]
    })
        .on('click', '.botonEliminar', function (evento) {
            evento.preventDefault();

            const urlProcesar = $(this).val()


            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Deseas eliminar el registro!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "si, eliminar!"
            }).then((result) => {
                if (result.isConfirmed) {


                    $.ajax({
                        url: urlProcesar,
                        method: 'DELETE',
                        data: {
                            _token: $('meta[name="csrf-token"]').attr('content')
                        }
                    }).done(function (respuesta) {
                        Swal.fire({
                            title: "Eliminado!",
                            text: respuesta.mensaje,
                            icon: "success"
                        });
                        tablaEstudiantes.ajax.reload();
                    });


                }
            });

        })
        .on('click', '.botonEstado', function (evento) {
            evento.preventDefault();

            const urlProcesar = $(this).val()

            const estado = $(this).text().trim();
            const nuevoEstado = estado == 'ACTIVO' ? 'INACTIVO' : 'ACTIVO';

            // console.log(nuevoEstado);

            $.ajax({
                url: urlProcesar,
                method: 'PATCH',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    estado: nuevoEstado
                }
            }).done(function (respuesta) {
                tablaEstudiantes.ajax.reload();
            })

        });


    $('#btnRegistrar').on('click', function () {

        modaEstudiante.show();

        $.get('/estudiante/create')
            .done(function (data) {
                console.log(data);

                $('#modalEstudiante .modal-content').html(data.html);
            })

    })


    // procesar el formulario

    $(document).on('submit', "#formEstudiante", function (evento) {
        evento.preventDefault(); // prevenir el comportamiento por defecto

        let formulario = $(this)

        let formData = new FormData(formulario[0])

        console.log(formulario.attr('action'));

        $.ajax({
            url: formulario.attr('action'),
            method: formulario.attr('method'),
            data: formData,
            processData: false,
            contentType: false,
        }).done(function (respuesta) {

            Swal.fire({
                title: "Exitoso!",
                text: "Estudiante registrado correctamente",
                icon: "success"
            });

            tablaEstudiantes.ajax.reload();

            modaEstudiante.hide();
            formulario[0].reset();

        })
            .fail(function (error) {

                Swal.fire({
                    title: "Error!",
                    text: "Debe completar los campos",
                    icon: "error"
                });

                console.log("error: ", error);
            })



    })


})




