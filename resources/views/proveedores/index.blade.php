@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Proveedores</h2>
    @endsection

    @section('content')
        <section class="box primary">
            <!--  PANEL HEADER    -->      
            <header class="panel_header">
                <h2 class="title pull-left">Lista de Proveedores</h2>
            </header>
            <div class="content-body">    
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <!-- <div class="pull-left">
                            <h2 class="bold">Proveedores</h2>
                        </div> -->
                        <div class="pull-right">
                            <a class="btn btn-info" href="{{ route('proveedores.create') }}">Nuevo Proveedor</a>
                        </div>
                    </div>
                </div>
                <div class="row top15">
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
                                <th width="200px">Acciones</th>
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
                                            <div class="acciones-btn">
                                                <a class='btn btn-orange' href="{{ route('proveedores.show',$proveedor->id) }}"><i class='fas fa-eye' aria-hidden='true'></i></a>
                                                <a class="btn btn-info" href="{{ route('proveedores.edit',$proveedor->id) }}"><i class="fas fa-edit" aria-hidden="true"></i></a>
                                                {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['proveedores.destroy', $proveedor->id],'style'=>'display:inline']) !!}
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