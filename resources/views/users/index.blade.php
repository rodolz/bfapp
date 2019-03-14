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
        </header>
        <div class="content-body">    
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="pull-right">
                        <a class="btn btn-info" id="nuevo_usuario">Nuevo Usuario</a>
                    </div>
                </div>
            </div>
            <div id="registro-div" hidden>
                <form name="registro-form" id="registro-form" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="nombre">Nombre</label>
                        <input type="text" name="nombre" id="nombre" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <span class="desc">para acceder al sistema</span>
                        <input type="email" name="email" id="email" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Password</label>
                        <input type="password" name="password_confirmation" id="confirm" class="form-control"/>
                    </div>
                    <div class="form-group">
                        <label for="rol">Rol</label>
                        <select class="form-control" id="rol">
                        @foreach($roles as $key => $value)
                        <option value={{ $key }}> {{ $value }} </option>
                        @endforeach
                        </select>
                    </div>
                    <div class="row">
                        <button type="submit" id="crear-usuario" class="btn btn-primary">Crear</button>
                    </div>
                </form>
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
                                        <a class="btn btn-info" href="{{ route('users.show',$user->id) }}"><i class="fas fa-user-circle"></i></a>
                                        {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['users.destroy', $user->id],'style'=>'display:inline']) !!}
                                        <button type="button" class="btn btn-danger">
                                            <i class="fas fa-trash-alt" aria-hidden="true"></i>
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


    // store form HTML markup in a JS variable
    var form = $('#registro-div > form').clone()[0];
    $('#registro-div').remove();

    // prepare SweetAlert configuration
    var swalConfig = {
    title: 'Nuevo Usuario',
    content: form,
    button: false
    };

    // handle clicks on the "Click me" button
    $('#nuevo_usuario').click(function () {
    swal(swalConfig);
    });

    $('body').on('click', '#crear-usuario', function() {
        var formData = $('#registro-form').serialize();
        $.ajax({
            // headers: {
            //     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            // },
            type: 'POST',
            url: '/register',
            data: formData,
            success: function(data, textStatus){
                if(data === "ok"){
                    swal({
                        title:"Usuario Creado!",
                        text: "Al cerrar será redireccionado a los usuarios",
                        icon: "success",
                        buttons: false,
                        timer: 1000
                        }).then(() => {
                            setTimeout(function(){
                            window.location.href = "{{URL::to('users')}}";
                            }, 1000);
                    });
                }
               console.log(data);
               console.log(data.status);
            },
            error: function( jqXHR ){
                console.log(jqXHR);
                console.log(jqXHR.responseText);
                console.log(jqXHR.status);
            } 
            
        });
    });
    // mock AJAX requests for this demo
    var isFakeAjaxRequestSuccessfull = false;

    function simulateAjaxRequest() {
    // "send" the fake AJAX request
    var fakeAjaxRequest = new Promise(function (resolve, reject) {
        setTimeout(function () {
        isFakeAjaxRequestSuccessfull ? resolve() : reject();
        isFakeAjaxRequestSuccessfull = !isFakeAjaxRequestSuccessfull;
        swal.stopLoading();
        }, 200);
    });

    // attach success and error handlers to the fake AJAX request
    fakeAjaxRequest.then(function () {
        // do this if the AJAX request is successfull:
        $('input.invalid').removeClass('invalid');
        $('.invalid-feedback').remove();
    }).catch(function () {
        // do this if the AJAX request fails:
        var errors = {
        nombre: 'Username is invalid',
        password: 'Password is invalid'
        };
        $.each(errors, function(key, value) {
        $('input[name="' + key + '"]').addClass('invalid').after('<div class="invalid-feedback">' + value + '</div>');
        });
    });
    }

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