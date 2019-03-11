@extends('layout.master')


    @section('page-title')
        <h2 class="title bold">Comisiones</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Lista de Comisiones</h2>
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
                        <a class="btn btn-info" href="{{ route('comisiones.create') }}">Nueva comisión</a>
                    </div>
                </div>
            </div>

            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th># Comisión</th>
                                <th>Fecha Creación</th>
                                <th>Repartidor</th>
                                <th>Monto Comisión</th>
                                <th width="150px">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                        @if(count($comisiones) > 0)
                            @foreach ($comisiones as $comision)
                                <tr>
                                    <th scope="row">{{ $comision->num_comision }}</th>
                                    <td>{{ $comision->created_at->format('d-m-Y') }}</td>
                                    <td>{{ $comision->repartidor->nombre }}</td>
                                    <td>${{ number_format($comision->monto_comision, 2, '.', ',') }}</td>
                                    <td>
                                        <div class="acciones-btn">
                                            <!-- <a class="btn btn-info" href="{{ route('comisiones.show',$comision->id) }}">Show</a> -->
                                            <!-- <a class="btn btn-primary" href="{{ route('comisiones.edit',$comision->id) }}">Modificar</a> -->
                                            <a class="btn btn-info" href="{{ URL::to('comision-pdf/'.$comision->id) }}"><i class="fa fa-file-pdf-o"></i></a>
                                            {!! Form::open(['method' => 'DELETE','onclick' => 'deletePrompt()','name' => 'deleteForm','route' => ['comisiones.destroy', $comision->id],'style'=>'display:inline']) !!}
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
                                <td colspan="5">
                                    <h2 class="bold text-warning text-center"><i class="fa fa-exclamation-triangle" aria-hidden="true" style="font-size:30px"></i> No se han agregado comisiones</h2>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- PAGINACION -->
            <center> {!! $comisiones->render() !!} </center>
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