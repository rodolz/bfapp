@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Categorías</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Categorías</h2>
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
                        <h2 class="bold">Categorias</h2>
                    </div> --}}
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('categorias.create') }}"> Nueva Categoría</a>
                    </div>
                </div>
            </div>

            <div class="row top15">
                	<div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Productos Registrados</th>
                                <th>Unidades Disponibles</th>
                                <th width="150px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                            @if(count($categorias) > 0)
                                @foreach ($categorias as $categoria)
                            	    <tr>
                            	        <th scope="row">{{ $categoria->nombre_categoria }}</th>
                            	        <td>{{ $categoria->productos->count() }}</td>
                            	        <td>
                            	        @php
                            	        $total = 0;
                            	        $collection = $categoria->productos->where('cantidad','>','0');
                            	        foreach ($collection as $producto) {
                            	        	$total += $producto['cantidad'];
                            	        }
                            	        @endphp
                            	        {{ $total }}
                            	        </td>
                            	        <td>
                                            <div class="acciones-btn">
                                                <!-- <a class="btn btn-info" href="{{ route('categorias.show',$categoria->id) }}">Show</a> -->
                                                <a class="btn btn-info" href="{{ route('categorias.edit',$categoria->id) }}"><i class="fas fa-edit" aria-hidden="true"></i></a>

                                                {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['categorias.destroy', $categoria->id],'style'=>'display:inline']) !!}
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fas fa-trash-alt" aria-hidden="true"></i>
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
        <center>{!! $categorias->render() !!}</center>
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