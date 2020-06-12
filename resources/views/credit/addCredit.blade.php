@extends('layouts.app')

@section('custom_header')
	  <!-- DataTables -->
	  <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
      <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">
@endsection

@section('content')

<div class=" mx-5">
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0 text-dark">Crear factura fiscal</h1>
                </div><!-- /.col -->
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Creditos</a></li>
                        <li class="breadcrumb-item active">Agregar credito</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    @if ( session('mensaje') )
    <div class="alert alert-success col-lg-8 mx-auto">{{ session('mensaje') }}</div>
    @endif
    <form class="form-horizontal" id="createOrderForm">
        @csrf
        <div class="row">
            <div class="col-sm-8">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <i class='glyphicon glyphicon-circle-arrow-right'></i> Ingrese la información requerida
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-6 form-group">
                                <label for="costumer" class="control-label">Vendido a:</label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                                    </div>
                                    <input type="hidden" id="idcostumer"/>
                                    <input type="text" class="form-control" placeholder="Buscar" id="costumer"
                                         name="costumer" aria-label="Enter..." aria-describedby="button-add"/>
                                    <div class="input-group-append">
                                        <button class="btn btn-outline-secondary" type="button" id="button-add" data-toggle="modal"
                                        data-target="#addCostumer"><i
                                                class="fas fa-plus"></i>Nuevo cliente</button>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="payment" class="control-label">Estado de pago</label>
                                <div>
                                    <select class="form-control" placeholder="Fecha">
                                        <option value="1">Completo</option>
                                        <option value="2">2 pagos</option>
                                        <option value="3">3 pagos</option>
                                        <option value="4">4 pagos</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group col-lg-3">
                                <label for="date" class="control-label">Fecha de factura</label>
                                <div>
                                    <input type="text" class="form-control" placeholder="Fecha"
                                        autocomplete="off" />
                                </div>
                            </div>
                        </div>

                         @include('product-order.table')

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <label class="col-sm-8  control-label">Estado de entrega</label>
                                <div class="col-sm-12">
                                    <select class="form-control" placeholder="Fecha">
                                        <option value="completo">Completo</option>
                                        <option value="parcial">Parcial</option>
                                        <option value="pendiente">Pendiente</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <label for="name" class="col-sm-8  control-label">Descuento</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="discounts" placeholder="Desc.." autocomplete="off" />
                                </div>
                                <label for="name" class="col-sm-8 mt-2 control-label">Cobros adcionales</label>
                                <div class="col-sm-12">
                                    <input type="number" class="form-control" name="mpayments" placeholder="Adicional.." autocomplete="off" />
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="col-sm-8 control-label">Terminos o comentarios</label>
                                <textarea class="form-control" placeholder="Comentarios adicionales"></textarea>
                            </div>
                        </div>
                        <!--Num tr value-->
                        <input type="hidden" name="trCount" id="trCount" autocomplete="off" class="form-control" />

                        <div class="form-group row mt-5">
                            <div class="col-sm-offset-2 col-sm-8">
                                <button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn"
                                data-loading-text="cargando..."> <i class="fa fa-plus-circle"></i> Añadir fila
                                </button>
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                    data-target="#SearchProducts"><i class="fa fa-search"></i>Agregar
                                    existentes</button>
                            </div>
                            <div class="col-sm-4">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <!-- card-->
                <div class="card card-outline card-danger">
                    <!-- /.card-header -->
                    <div class="card-body">
                        <h4 class="mb-3">Resumen</h4>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Cantidad total
                                <strong id="grandquantity">0</strong>
                                <input type="hidden" id="grandquantityvalue" name="grandquantityvalue">
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Sub total
                                <strong id="">$0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Descuentos
                                <strong id="">$0.00</strong>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total
                                <strong id="grandtotal">$0.00</strong>
                                <input type="hidden" id="grandtotalvalue" name="grandtotalvalue">
                            </li>
                        </ul>
                        <button type="submit" id="createSale" data-loading-text="Cargando..."
                            class="btn btn-success btn-block mt-2">Registrar factura</button>
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->
            </div>
        </div>
    </form>
</div>

@include('product-order.modal')

<!-- ---------------------------------------------------------------------------------- -->
<!-- --------------------Modal-------------------- -->
<!-- ---------------------------------------------------------------------------------- -->

<div class="modal fade right" tabindex="-1" role="dialog" id="addCostumer">
    <div class="modal-dialog modal-full-height modal-right">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Crear Nuevo cliente</h4>
                <button class="close ml-2" data-dismiss="modal" arial-label="close"><span
                    aria-hidden="true">x</span></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <i class="far fa-user fa-4x text-primary fa-rotate-right mb-1"></i>
                </div>
                <form action="{{ route('makeCostumer') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Codigo: </label>
                        <div class="col-sm-12">
                            <input type="number" class="form-control" placeholder="Nombre" name="code"
                                autocomplete="off" value="{{ old('code') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Nombre: </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" placeholder="Nombre" name="name"
                                autocomplete="off" value="{{ old('name') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">NIT: </label>
                        <div class="col-sm-12">
                            <input type="tel" class="form-control" placeholder="Nombre" name="nit"
                                autocomplete="off" value="{{ old('nit') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Telefono: </label>
                        <div class="col-sm-12">
                            <input type="tel" class="form-control" placeholder="Nombre" name="phone"
                                autocomplete="off" value="{{ old('phone') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Email: </label>
                        <div class="col-sm-12">
                            <input type="email" class="form-control" placeholder="Nombre" name="email"
                                autocomplete="off" value="{{ old('email') }}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="name" class="col-sm-3 control-label">Direccion: </label>
                        <div class="col-sm-12">
                            <input type="text" class="form-control" placeholder="Nombre" name="address"
                                autocomplete="off" value="{{ old('address') }}">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary btn-block" data-loading-text="Loading..."
                        autocomplete="off"> <i class="fas fa-check-circle"></i> Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('custom_footer')
    <!-- DataTables -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>
    
    @include('product-order.script')

    <script>
        $('#createOrderForm').unbind('submit').bind('submit', function (stay) {
            stay.preventDefault();
            var formdata = $(this).serialize();
            var url = "{{ route('createCredit') }}";
            $.ajax({
                type: 'POST',
                url: url,
                data: formdata,
                beforeSend: function () {
                    //Loader
                },
                success: function (response) {
                    console.log(response);
                    Swal.fire({
                        position: 'top-end',
                        type: 'success',
                        title: response.message,
                        showConfirmButton: false,
                        timer: 1500
                    });
                    //Clear all fields
                    $('#createOrderForm').closest('form').find("input[type=text], input[type=number], textarea").val("");
                    print(response.data);
                },
                error: function (xhr, textStatus, errorMessage) {
                    Swal.fire({
                        position: 'top',
                        type: 'error',
                        html: 'Error crítico: ' + xhr.responseText,
                        showConfirmButton: true,
                    });
                }
            });
        });

        function print(data) {
            var invoice = data.invoice;
            var target = window.open('', 'PRINT', 'height=800,width=800');
            target.document.write(invoice);
            target.print();
            target.close();
        }
    </script>

@endsection