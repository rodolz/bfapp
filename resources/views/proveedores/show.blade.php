@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Proveedores</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left"><strong>{{ $proveedor->name }}</strong></h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row">
                        <div class="pull-left">
                            <h2 class="title bold">Resumen</h2>
                        </div>
                    </div>
                    <div class="well row top15">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Email</th>
                                        <th>Website</th>
                                        <th>Direccion</th>
                                        <th>Ciudad</th>
                                        <th>Pais</th>
                                        <th>PostCode</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <th>{{ $proveedor->name }}</th>
                                        <th>{{ $proveedor->email }}</th>
                                        <th>{{ $proveedor->website }}</th>
                                        <th>{{ $proveedor->address }}</th>
                                        <th>{{ $proveedor->city }}</th>
                                        <th>{{ $proveedor->country }}</th>
                                        <th>{{ $proveedor->postcode }}</th>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">
                        <div class="row">
                            <div class="text-center">
                                <h2 class="bold">Productos</h2>
                            </div>
                        </div>
                        <div class="well row top15 right15">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Codigo</th>
                                            <th>Descripcion</th>
                                            <th>Precio</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @if(count($productos_proveedor) > 0)
                                        @foreach ($productos_proveedor as $producto)
                                            <tr>
                                                <th>{{ $producto->codigo }}</th>
                                                <th>{{ $producto->descripcion }}</th>
                                                <th>${{ number_format($producto->precio, 2, '.', ',') }}</th>
                                                {!! Form::open(['method' => 'DELETE','route' => ['proveedores_producto.destroy', $producto->id],'style'=>'display:inline']) !!}
                                                <th>
                                                <button type="submit" class="btn btn-danger">
                                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                                </button>
                                	            {!! Form::close() !!}
                                                </th>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4">
                                                <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado productos </h2>
                                            </td>
                                        </tr>
                                    @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            <div class="row text-center">
                 <a type="button" class="btn" href="{{ URL::route('productos.index') }}">Atras</a>
            </div>
        </div>
    </section>
@endsection