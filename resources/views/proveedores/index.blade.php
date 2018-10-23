@extends('layout.master')


@section('page-title')
    <h2 class="title bold">Proveedores</h2>
@endsection

@section('panel-title')
   <h2 class="title pull-left">Lista de Proveedores</h2>
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
                <!-- <div class="pull-left">
                    <h2 class="bold">Proveedores</h2>
                </div> -->
                <div class="pull-right">
                    <a class="btn btn-info" href="{{ route('proveedores.create') }}"> Nuevo Proveedor</a>
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
                            <th>Nombre</th>
                            <th>Email</th>
                            <th>Direccion</th>
                            <th>Ciudad</th>
                            <th>Pais</th>
                            <th>Codigo Postal</th>
                            <th>Website</th>
                            <th width="150px">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(count($proveedores) > 0)
                            @foreach ($proveedores as $proveedor)
                                <tr>
                                    <th scope="row">{{ $proveedor->name }}</th>
                                    <td>{{ $proveedor->email }}</td>
                                    <td>{{ $proveedor->address }}</td>
                                    <td>{{ $proveedor->city }}</td>
                                    <td>{{ $proveedor->country }}</td>
                                    <td>{{ $proveedor->postcode }}</td>
                                    <td>{{ $proveedor->website }}</td>
                                    <td>
                                        <a class='btn btn-orange' href="{{ route('proveedores.show',$proveedor->id) }}"><i class='fa fa-eye' aria-hidden='true'></i></a>
                                        <a class="btn btn-info" href="{{ route('proveedores.edit',$proveedor->id) }}"><i class="fa fa-pencil" aria-hidden="true"></i></a>
                                        {!! Form::open(['method' => 'DELETE','route' => ['proveedores.destroy', $proveedor->id],'style'=>'display:inline']) !!}
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="9">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado Proveedores</h2>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                    </table>
                </div>
        </div>
   <!-- PAGINACION -->
    <center>{!! $proveedores->render() !!}</center>
    </div>
</section>
@endsection

@section('add-plugins')

@endsection