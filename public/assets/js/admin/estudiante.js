$(function () {

    'use strict';

    let modaEstudiante = new bootstrap.Modal(document.querySelector('#modalEstudiante'));


    let tablaEstudiantes =  $('#tablaEstudiantes').DataTable({
        processing: true,
        serverSide:true,
        ajax: '/estudiante',
        columns:[
            {data: 'id', name:'id'},
            {data: 'nombre', name:'nombre'},
            {data: 'paterno', name:'paterno'},
            {data: 'materno', name:'materno'},
            {data: 'ci', name:'ci'},
            // {data: 'fecha_nacimiento', name:'fecha_nacimiento'},
            // {data: 'acciones', name:'acciones', orderable:false, searchable:false},
        ]
    });


    $('#btnRegistrar').on('click', function () {

        modaEstudiante.show();

        $.get('/estudiante/create')
            .done(function (data) {
                console.log(data);
                tablaEstudiantes.ajax.reload();

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




