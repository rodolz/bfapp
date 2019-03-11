@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Usuarios</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Usuarios</h2>
    @endsection

@section('content')
    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            @yield('panel-title')
            {{-- <div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                <i class="box_close fa fa-times"></i>
            </div>  --}}
        </header>
        <div class="content-body">    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-right">
                        <a class="btn btn-info" href="{{ route('users.create') }}">Nuevo Usuario</a>
                    </div>
                </div>
            </div>

            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Correo</th>
                                <th>Roles</th>
                                <th>Fecha de Creación</th>
                                <th width="150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($users) > 0)
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nombre }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>
                                        @foreach ( $user->roles as $role)
                                            {{ $role->name }}
                                        @endforeach
                                    </td>
                                    <td> {{ $user->created_at->format('d/m/Y') }} </td>
                                    <td>
                                        <!-- <a class="btn btn-info" href="{{ route('users.show',$user->id) }}">Show</a> -->
                                        <!-- <a class="btn btn-primary" href="{{ route('users.edit',$user->id) }}">Modificar</a> -->
                                        {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fa fa-trash-o" aria-hidden="true"></i>
                                        </button>
                                        {!! Form::close() !!}
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado Usuarios</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- PAGINACION -->
            <center> {!! $users->render() !!} </center>
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