@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Pagos</h2>
    @endsection

@section('content')
    <section class="box primary">
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">
           <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-left">
                        <h2 class="bold">Pagos</h2>
                    </div>
                </div>
            </div>

            <div class="row top15">
                	<div class="table-responsive">
                        <table id="tabla_pagos" class="table table-hover">
                        <thead>
                            <tr>
                            </tr>
                    	</thead>
                        <tbody>
                        </tbody>
                        </table>
                    </div>
            </div>
        </div>
    </section>
@endsection

@section('add-plugins')
<script type="text/javascript">

    $(function(){
        $('#tabla_pagos').DataTable({
            aaSorting: [],
            autoWidth: true,
            language: {
                "decimal": ",",
                "thousands": "."
            },
            processing: true,
            bServerSide: true,
            ajax: {
                type: 'GET',
                contentType: "application/json; charset=utf-8",
                url: "pagos/getdata",
            },
            columns: [
                {data: 'cliente.empresa', name: 'cliente.empresa', title: 'Cliente', searchable: true, orderable:false },
                {data: 'numero_referencia', name: 'numero_referencia', title: '# Referencia' },
                {data: 'descripcion', name: 'descripcion', title: 'Descripcion'},
                {
                    data: 'monto_pago', name: 'monto_pago', title: 'Monto',
                    render: $.fn.dataTable.render.number( ',', '.', 2, '$' )
                },
                {data: 'banco', name: 'banco', title: 'Banco'},
                {data: 'created_at', header:'Y-m-d', name: 'created_at', title: 'Fecha', type: 'date',},
                {data: 'action', name: 'action', orderable: false, searchable: false},
            ]
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