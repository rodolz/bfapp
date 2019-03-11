@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Ship to</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Direcciones Shipto</h2>
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
                    {{-- <div class="pull-left">
                        <h2 class="bold">Direcciones Shipto</h2>
                    </div> --}}
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('shipto.create') }}">Nueva Direccion <strong>Shipto</strong></a>
                    </div>
                </div>
            </div>

            <div class="row top15">
                	<div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th># de POs Asociadas</th>
                                <th>Direccion</th>
                                <th>Pais</th>
                                <th>Estado</th>
                                <th>Ciudad</th>
                                <th width="150px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                            @if(count($shiptos) > 0)
                                @foreach ($shiptos as $shipto)
                            	    <tr>
                                        <th scope="row">{{ $shipto->nombre_shipto }}</th>
                                        <td>{{ $shipto->pos->count() }}</td>
                                        <td>{{ $shipto->address_line1 }}</td>
                                        <td>{{ $shipto->country }}</td>
                                        <td>{{ $shipto->state }}</td>
                                        <td>{{ $shipto->city }}</td>
                            	        <td>
                                            <div class="acciones-btn">
                                                <!-- <a class="btn btn-info" href="{{ route('shipto.show',$shipto->id) }}">Show</a> -->
                                                <a class="btn btn-info" href="{{ route('shipto.edit',$shipto->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                                {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['shipto.destroy', $shipto->id],'style'=>'display:inline']) !!}
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                                {!! Form::close() !!}
                                            </div>
                            	        </td>
                            	    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="9">
                                        <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado categorias</h2>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
            </div>
       <!-- PAGINACION -->
        <center>{!! $shiptos->render() !!}</center>
        </div>
    </section>
@endsection

@section('add-plugins')
<script>
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