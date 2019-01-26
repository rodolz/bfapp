@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Categorías</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nueva Categoría</h2>
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
       		 {!! Form::open(array('route' => 'shipto.store','method'=>'POST')) !!}
                <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12 col-lg-offset-3">
                    <div class="form-group">
                        <label class="form-label" for="field-1">Nombre de la direccion 'Shipto'</label>
                        <span class="desc">"nombre para identificar la direccion"</span>
                        <div class="controls">
                            {!! Form::text('nombre_shipto', null, array('placeholder' => 'Bodega 1 - Costa del este','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Nombre de la empresa</label>
                        <span class="desc">"BF Services S.A por default"</span>
                        <div class="controls">
                            {!! Form::text('name', "BF Services S.A", array('placeholder' => 'Vinil','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Direccion</label>
                        <span class="desc">"la segunda linea es opcional"</span>
                        <div class="controls">
                            {!! Form::text('address_line1', null, array('placeholder' => 'Parque Industrial Costa del Este','class' => 'form-control')) !!}
                        </div>
                        <div class="controls">
                            {!! Form::text('address_line2', null, array('placeholder' => 'Edificio IStorage, Local #1234','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Pais</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('country', null, array('placeholder' => 'Venezuela','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Estado</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('state', null, array('placeholder' => 'Distrito Capital','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Ciudad</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('city', null, array('placeholder' => 'Caracas','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="field-1">Telefono</label>
                        <span class="desc">(+507) 6330-1307 por default</span>
                        <div class="controls">
                            {!! Form::text('phone', '(+507) 6330-1307', array('placeholder' => '(+507) 6330-1307','class' => 'form-control')) !!}
                        </div>
                    </div>
                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-4 padding-bottom-30">
                        <div class="row">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                             <a type="button" class="btn" href="{{ URL::route('shipto.index') }}">Cancelar</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection