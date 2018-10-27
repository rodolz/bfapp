@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Clientes</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Clientes</h2>
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
                {{--     <div class="pull-left">
                        <h2 class="bold">Clientes</h2>
                    </div> --}}
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('clientes.create') }}"> Nuevo Cliente</a>
                    </div>
                </div>
            </div>

            <div class="row top15">

             <!--    @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}</p>
                    </div>
                @endif -->
                	<div class="table-responsive">
                        <table class="table table-hover">
                        <thead>
                            <tr>
                                <th width="150px">Empresa</th>
                                <th>Contacto</th>
                                <th width="150px">Tel-Local</th>
                                <th width="105px">Tel-Celular</th>
                                <th width="450px">Direccion</th>
                                <th>Email</th>
                                <th>WWW</th>
                                <th>RUC</th>
                                <th width="150px">Acciones</th>
                            </tr>
                    	</thead>
                        <tbody>
                            @if(count($clientes) > 0)
                                @foreach ($clientes as $key => $value)
                            	    <tr>
                            	        <th scope="row">{{ $value->empresa }}</th>
                            	        <td>{{ $value->contacto }}</td>
                            	        <td>{{ $value->tel_local }}</td>
                            	        <td>{{ $value->tel_celular }}</td>
                            	        <td>{{ $value->direccion }}</td>
                            	        <td>{{ $value->email }}</td>
                                        <td>{{ $value->www }}</td>
                                        <td>{{ $value->ruc }}</td>
                            	        <td>
                                            <div class="acciones-btn">
                                	            <!-- <a class="btn btn-info" href="{{ route('clientes.show',$value->id) }}">Show</a> -->
                                	            <a class="btn btn-info" href="{{ route('clientes.edit',$value->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                	            {!! Form::open(['method' => 'DELETE','route' => ['clientes.destroy', $value->id],'style'=>'display:inline']) !!}
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
                                        <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado clientes</h2>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                        </table>
                    </div>
            </div>
       <!-- PAGINACION -->
        <center>{!! $clientes->render() !!}</center>
        </div>
    </section>
@endsection

@section('add-plugins')
<!--         <script>
            swal({
              title: "Are you sure?",
              text: "You will not be able to recover this imaginary file!",
              type: "warning",
              showCancelButton: true,
              confirmButtonColor: "#DD6B55",
              confirmButtonText: "Yes, delete it!",
              closeOnConfirm: false
            },
            function(){
              swal("Deleted!", "Your imaginary file has been deleted.", "success");
            });
        </script> -->
@endsection