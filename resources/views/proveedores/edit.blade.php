@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Clientes</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Modificando a <strong>{{ $cliente->empresa }}</strong></h2>
    @endsection

@section('content')

    <section class="box primary">
        <!--  PANEL HEADER    -->      
        <header class="panel_header">
            <h2 class="title pull-left">@yield('panel-title')</h2>
            <!--<div class="actions panel_actions pull-right">
                <i class="box_toggle fa fa-chevron-down"></i>
                <i class="box_setting fa fa-cog" data-toggle="modal" href="#section-settings"></i>
                <i class="box_close fa fa-times"></i>
            </div> -->
        </header>
        <div class="content-body">    
            <div class="row">
               {!! Form::model($cliente, ['method' => 'PATCH','route' => ['clientes.update', $cliente->id]]) !!}
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-2">
                   <div class="form-group">
                        <label class="form-label" for="field-1">Empresa</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('empresa', null, array('placeholder' => 'Nombre de la Empresa','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Contacto</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('contacto', null, array('placeholder' => 'Nombre y Apellido','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-6">Telefono Local</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('tel_local', null, array('placeholder' => '273-2134','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Telefono Celular</label>
                        <span class="desc"><i>"si no lo conoce escriba N/A"</i></span>
                        <div class="controls">
                            {!! Form::text('tel_celular', null, array('placeholder' => '6789-5143','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-6">Direccion</label>
                        <span class="desc"><i>"descripcion breve de la direccion"</i></span>
                        <div class="controls">
                            {!! Form::textarea('direccion', null, array('size' => '50x3', 'class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Email</label>
                        <span class="desc"><i>"si no lo conoce escriba N/A"</i></span>
                        <div class="controls">
                            {!! Form::text('email', null, array('placeholder' => 'nombre@email.com','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Pagina Web</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('www', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">RUC</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('ruc', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-4 padding-bottom-30">
                        <div class="row">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                            <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>

@endsection