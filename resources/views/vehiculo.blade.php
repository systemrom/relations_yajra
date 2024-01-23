<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>VIEW | Relations</title>
    <style>
        *{
            margin: 0;
            padding: 0
            box-sizing: margin-box;
            box-shadow: none !important;
        }
    </style>




    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
 --}}

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
    integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/jquery.dataTables.min.css">
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>


    {{-- SELECT2 --}}
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    {{-- Sweetalert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



    {{-- Genera el token para AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">


</head>
<body>

    <div class="container jsutify-content-center">
        <div class="row justify-content-center">
            <div class="col-lg-2 col-md-10 col-sm-10 mt-2">
                <button type="button" class="btn btn-outline-success mt-2 mb-2" data-bs-toggle="modal" id="action"
                    data-bs-target="#vehiculoAddModal">
                    Agregar
                </button>

                <label for="">Agregar</label>
            </div>
            <div class="border col-lg-10 col-md-10 col-sm-10">
                <form action="" class="form_search justify-content-center mt-2">
                    <div class="row d-flex">
                        <div class="form-group col-4 d-flex" style="margin-right: 10px">
                            <label for="">Compania</label>
                            <select name="compania" id="compania" class="select2 form-control" style="width: 200px;">
                                <option value="">Seleccionar</option>
                                @foreach ($companias as $companiaId => $companiaNombre)
                                    <option value="{{ $companiaId }}">{{ $companiaNombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-4 d-flex" style="margin-left: 10px">
                            <label for="">Placa</label>
                            <input type="text" name="placa" id="placa" placeholder="XTY-784" class="form-control">
                        </div>
                    </div>
                    <div class="mt-2 d-flex justify-content-center">
                        <button type="submit" class="btn btn-outline-primary" id="generate" style="margin-right: 10px;">Filter</button>
                        <button type="button" class="btn btn-outline-secondary" id="reset" style="margin-left: 10px;">Reset</button>
                    </div>
                </form>
                <table id="vehiculo_datatable" class="table table-striped nowrap vehiculo_datatable pt-1 w-100" style="width: 100%; margin-top: 10px">
                    <thead>
                        <tr>
                            <th>COMPAÑIA</th>
                            <th>PLACA</th>
                            <th>VENDEDOR</th>
                            <th>PESO</th>
                            <th>PAQUETES</th>
                            <th>VOLUMEN</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>




    {{-- EMPIEZA MODAL DE AGREGAR REGISTRO --}}
    <div class="modal fade" id="vehiculoAddModal" tabindex="-1" aria-labelledby="vehiculoModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="post" class="formAgregarVehiculo" id="vehiculoModal">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="vehiculoModalLabel">Agregar nuevo vehículo</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <span id="form_result"></span>
                        <div class="form-group">
                            <label for="">Placa</label>
                            <input type="text" name="placa" id="placa" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Capacidad peso</label>
                            <input type="text" name="peso" id="peso" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Cpacidad paquete</label>
                            <input type="text" name="paquete" id="paquete" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Capacidad volumen</label>
                            <input type="text" name="volumen" id="volumen" class="form-control" required>
                        </div>
                        <div class="form-group mt-2 pb-4" style="height: auto; z-index: 100;">
                            <label for="">Compañias</label>
                            <select name="id_compania" id="id_compania" class="form-control">
                                <option value="">Seleccionar</option>
                                @foreach ($companias as $companiaId => $companiaNombre)
                                    <option value="{{ $companiaId }}">{{ $companiaNombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <input type="submit" name="action" id="action" class="btn btn-outline-primary"
                            value="Guardar">
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- EMPIEZA CODIGO JS Y AJAX --}}
    <script type="text/javascript">

        var table;

        $(function() {

            // Inicializando SELECT2
            $('.select2').select2();


            // Inicializando datatable
            table = $('#vehiculo_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vehiculo.index') }}"
                },
                columns: [
                    { data: 'compania_nombre', name: 'compania_nombre' },
                    { data: 'placa', name: 'placa' },
                    { data: 'vendedor_nombre', name: 'vendedor_nombre' },
                    { data: 'peso', name: 'peso' },
                    { data: 'paquete', name: 'paquete' },
                    { data: 'volumen', name: 'volumen' },
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ], 
                columnDefs: [
                    {
                        targets: -1,
                        render: function(data, type, row, meta){
                            return '<a href="javascript:void(0)" id="show" data-url="/vehiculo/show/' + row.id + '" class="btn btn-outline-secondary show-vehiculo mb-1">Show</a>' +
                                '<button type="button" name="delete" data-id="' + row.id + '" class="delete btn btn-outline-danger btn-sm">Delete</button>';
                        },
                    },
                ]
            });

            //Se esta agregando solo esto
            $('#vehiculoModal').submit(function(event) {
                // Evita que el formulario se envíe de la manera tradicional
                event.preventDefault();

                // Muestra el cuadro de diálogo de confirmación
                Swal.fire({
                    title: '¿Está seguro de agregar el automóvil?',
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#20c997',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Confirmar'
                }).then((result) => {
                    if (result.isConfirmed) { //Si se confirma el envío entonces realiza la solicitud AJAX
                        $.ajax({
                            type: 'post',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            url: "{{ route('vehiculo.store') }}",
                            data: $('#vehiculoModal').serialize(), // Serializa los datos del formulario
                            dataType: 'json',
                            success: function(data) {
                                console.log('success: ' + data);
                                var html = '';

                                if (data.errors) {
                                    html = '<div class="alert alert-danger">';
                                    for (var key in data.errors) {
                                        if (data.errors.hasOwnProperty(key)) {
                                            html += '<p>' + data.errors[key][0] + '</p>';
                                        }
                                    }
                                    html += '</div>';
                                    $('#form_result').html(html);
                                }

                                if (data.message) {
                                    html = '<div class="alert alert-success">' + data.message + '</div>';
                                    $('#vehiculoModal')[0].reset();
                                    $('#form_result').html(html);
                                    if ($.fn.DataTable.isDataTable('#vehiculo_datatable')) {
                                        $('#vehiculo_datatable').DataTable().ajax.reload();
                                    }
                                    Swal.fire('¡Automóvil agregado!',
                                        'El registro ha sido agregado correctamente a la base de datos',
                                        'success'
                                    );
                                }
                            },
                            error: function(data) {
                                var errors = data.responseJSON;
                                console.log(errors);
                            }
                        });
                    } else { //Si no se confirma el envío entonces manda un alert de cancelado.
                        Swal.fire('¡Vehículo no agregado!',
                            'Se ha cancelado el registro del vehículo',
                            'error'
                        );
                    }
                });
            });
            //hasta aqui

            // Funcion de filtro
            $('#generate').on('click', function(event){
                // Evitar que el formulario se envíe por defecto
                event.preventDefault();

                // Obtiene los datos del filtro
                var companiaInput = $('#compania').val();
                var placaInput = $('#placa').val();

                console.log('compania: ' + companiaInput);
                console.log('placa: ' + placaInput);

                // Recarga el datatables con los valores de filtro
                table.ajax.url("{{ route('vehiculo.index') }}?compania=" + companiaInput + "&placa=" + placaInput).load(null, 'reload');

                return false;
            });

            // Funcion para resetear el filtro
            $('#reset').on('click', function(){
                // Limpiar datos
                $('#compania').val('').trigger('change');
                $('#placa').val('');

                // Recargar dataTable sin filtro
                table.ajax.url("{{ route('vehiculo.index') }}").load(null, 'reload');

                return false;
            });
    });


        /*
        $(function(){
            //Inicializando datatable
            var table = $('#vehiculo_datatable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('vehiculo.index') }}"
                },
                columns: [
                    { data: 'compania_nombre', name: 'compania_nombre' },
                    { data: 'placa', name: 'placa' },
                    { data: 'vendedor_nombre', name: 'vendedor_nombre' },
                    { data: 'peso', name: 'peso' },
                    { data: 'paquete', name: 'paquete' },
                    { data: 'volumen', name: 'volumen' },
                    { data: null, defaultContent: '', orderable: false, searchable: false },
                ]
            });
            //Inicializando SELECT2
            $('.select2').select2();



        });
        */
</script>

</body>
</html>
