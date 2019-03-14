@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Productos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Productos Registrados</h2>
    @endsection


@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->
        <header class="panel_header">
            @yield('panel-title')
            <!--<div class="actions panel_actions pull-right">
            <i class="box_toggle fa fa-chevron-down"></i>
            <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
            <i class="box_close fa fa-times"></i>
            </div> -->
        </header>
        <div class="content-body">
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="bold">Productos</h2>
                    </div>
                    <div class="pull-right">
                        <a class="btn btn-info top15" href="{{ route('productos.create') }}"> Nuevo Producto</a>
                        <a class="btn btn-default top15" href="{{ route('categorias.create') }}"> Nueva Categoria</a>
                    </div>
                </div>
            </div>
            <div class="row top15">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table id="tabla_productos" class="table table-hover">
                            <thead>
                                <tr>
                                <th>Codigo</th>
                                <th>Categorias</th>
                                <th>Descripcion</th>
                                <th>Medidas</th>
                                <th>Precio Venta</th>
                                <th>Precio Costo</th>
                                <th width="125px">Cantidad Disp.</th>
                                <th width="200px">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('add-plugins')
<script type="text/javascript">

    $(function(){
        $('#tabla_productos').DataTable({
            aaSorting: [],
            autoWidth: true,
            language: {
                "decimal": ".",
                "thousands": ","
            },
            processing: true,
            bServerSide: true,
            ajax: {
                type: 'GET',
                contentType: "application/json; charset=utf-8",
                url: "productos/getdata",
            },
            columns: [
                {data: 'codigo', name: 'codigo'},
                {data: 'categoria.nombre_categoria', name: 'categoria.nombre_categoria', title: 'Categoria',searchable: true, orderable:true },
                {data: 'descripcion', name: 'descripcion'},
                {data: 'medidas', name: 'medidas'},
                {
                    data: 'precio', name: 'precio',
                    render: function ( data, type, full, meta ) {
                                // var num = $.fn.dataTable.render.number( ',', '.', 2).display(data);
                                return '<div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" style="width: 7.5em" maxlength="4" size="4" type="number" name="'+full.id+'" id="precio" value="'+data+'"/>';
                            }
                },
                {
                    data: 'precio_costo', name: 'precio_costo',
                    render: function ( data, type, full, meta ) {
                                return '<div class="input-group"><span class="input-group-addon"><i class="fas fa-dollar-sign"></i></span><input class="form-control" style="width: 7.5em" maxlength="4" size="4" type="number" name="'+full.id+'" id="precio_costo" value="'+data+'" />';
                            }
                },
                {
                    data: 'cantidad', name: 'cantidad',
                    render: function ( data, type, full, meta ) {
                                // console.log(full.id);
                                return '<input class="form-control" style="width: 7.5em" maxlength="4" size="4" type="number" name="'+full.id+'" id="cantidad" value="'+ data+'" />';
                            }
                },
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
        });
    });
    
    $(document).on('focusin', ':input[type="number"]', function() {
        window.currentValue = $(this).val();
    });

    $(document).on('focusout', ':input[type="number"]', function() {
        var value = $(this).val();
        var type = $(this).attr('id');
        if(value == window.currentValue){
            return false;
        }
        var idProducto = $(this).attr('name');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type : 'POST',
            url  : '/productos/update',
            data : {
                'value' : value,
                'idProducto' : idProducto,
                'type' : type
            },
            success: function( data, textStatus, jQxhr ){
                if(data === "ok"){
                    swal({
                        title:"Producto Actualizado!",
                        text: "Los cambios se han guardado",
                        type: "success",
                        timer: 500,
                        showConfirmButton: false,
                        });
                }
                else{
                    alert(data);
                    var errors = "<p>"+data+"</p>";
                    swal({
                        type: 'error',
                        title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                        text: errors,
                        html: true
                    });
                }
            },
            error: function( data ){
                // Error...
                console.log(errors);
                console.log(data);
                var errors = "<p>"+data.responseText+"</p>";
                swal({
                    type: 'error',
                    title: "Hubo un error, contacte al ADMIN con el siguiente error:",
                    text: errors,
                    customClass: 'sweet-alert-lg',
                    html: true
                });
            }
        });
    });

function deletePrompt() {
    event.preventDefault(); // prevent form submit
    var form = document.forms["deleteForm"]; // storing the form
    swal({
        title: "Esta seguro/a de eliminar?",
        text: "Si procede no se podrá recuperar esta información",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
    .then((result) => {
        if (result) {
            form.submit();
        }
    });
}
</script>


@endsection
