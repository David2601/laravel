@extends('layouts.main')
@section('contenido')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Productos</h1>

        <div class="col-4">
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#modalAgregar">
                <i class="fas fa-fw fa-file fa-sm text-white-50"></i> Agregar Producto
            </a>

            <!--<a href="/admin/productos/imprimir" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-fw fa-print fa-sm text-white-50"></i> Imprimir
            </a>-->
        </div>

    </div>
    <div class="row">
        @if ($message = Session::get('Listo'))
            <div class="col-12 alert alert-success alert-dismissable fade show" role="alert">
                <h5>Mensaje: </h5>
                <span>{{ $message }}</span>
            </div>
        @endif

        <div class="row col-6">
            <canvas id="myChart"></canvas>
        </div>

        <table class="table col-12">
            <thead>
                <tr>
                    <td>Id</td>
                    <td>Nombre</td>
                    <td>Stock</td>
                    <td>Codigo</td>
                    <td>&nbsp;</td>
                </tr>
            </thead>
            <tbody id="tbody">

            </tbody>
            </tabe>
    </div>
    <!-- Modal Agregar -->
    <div class="modal fade" id="modalAgregar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Agregar Producto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action='/admin/productos' method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        @if ($message = Session::get('ErrorInsert'))
                            <div class="col-12 alert alert-danger alert-dismissable fade show" role="alert">
                                <h5>Errores: </h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <div class="form-group">
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre"
                                value="{{ old('nombre') }}">
                        </div>
                        <div class="form-group">
                            <input type="file" class="form-control" name="img" placeholder="Imagen">
                        </div>
                        <div class="form-group">
                            <input type="number" class="form-control" name="stock" placeholder="stock">
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="codigo" placeholder="Codigo">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Eliminar -->
    <div class="modal fade" id="modalEliminar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Eliminar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5>¿Desea eliminar el usuario?</h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-danger btnModalEliminar">Eliminar</button>
                </div>

            </div>
        </div>
    </div>

    <!-- Modal Editar -->
    <div class="modal fade" id="modalEditar" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Editar Usuario</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action='/admin/usuarios/edit' method="post">
                    @csrf
                    <div class="modal-body">
                        @if ($message = Session::get('ErrorInsert'))
                            <div class="col-12 alert alert-danger alert-dismissable fade show" role="alert">
                                <h5>Errores: </h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        <input type="hidden" name="id" id="idEdit">
                        <div class="form-group">
                            <input type="text" class="form-control" name="nombre" placeholder="Nombre"
                                value="{{ old('nombre') }}" id="nameEdit">
                        </div>
                        <div class="form-group">
                            <input type="email" class="form-control" name="email" placeholder="Correo Electronico"
                                value="{{ old('email') }}" id="emailEdit">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="pass1" placeholder="Contraseña">
                        </div>
                        <div class="form-group">
                            <input type="password" class="form-control" name="pass2"
                                placeholder="Confirmar Contraseña">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Editar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.0.1/chart.min.js"></script>
    <script>
        var idEliminar = 0;
        var productos = [];
        var valores = [];

            $(document).ready(function() {

                $.ajax({
                    url: '/admin/productos/all',
                    method: 'POST',
                    data: {
                        id: 1,
                        _token: $('input[name="_token"]').val()
                    }
                }).done(function(res) {
                    var arreglo = JSON.parse(res);

                    for (var x = 0; x < arreglo.length; x++) {
                        var todo = '<tr><td>' + arreglo[x].id + '</td>';
                        todo += '<td>' + arreglo[x].nombre + '</td>';
                        todo += '<td>' + arreglo[x].stock + '</td>';
                        todo += '<td>' + arreglo[x].codigo + '</td>';
                        todo += '<td></td></tr>';
                        $('#tbody').append(todo);
                        productos.push(arreglo[x].nombre);
                        valores.push(arreglo[x].stock);
                    }
                    generarGrafica();
                });


                @if ($message = Session::get('ErrorInsert'))
                    $("#modalAgregar").modal('show');
                @endif
                $(".btnEliminar").click(function() {
                    idEliminar = $(this).data('id');
                });
                $(".btnModalEliminar").click(function() {
                    $("#formEli_" + idEliminar).submit();
                });
                $(".btnEditar").click(function() {
                    $("#idEdit").val($(this).data('id'));
                    $("#nameEdit").val($(this).data('name'));
                    $("#emailEdit").val($(this).data('email'));
                });
            });

        function generarGrafica() {
            const ctx = document.getElementById('myChart').getContext('2d');

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: productos,
                    datasets: [{
                        label: 'Productos en inventario',
                        data: valores,
                        backgroundColor: [
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)',
                        ],
                        backgroundColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)',
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }
    </script>
@endsection
