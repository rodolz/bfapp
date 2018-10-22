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
       		 {!! Form::open(array('route' => 'categorias.store','method'=>'POST')) !!}
                <div class="col-lg-6 col-md-6 col-sm-9 col-xs-12 col-lg-offset-3">
                    <div class="form-group">
                        <label class="form-label" for="field-1">Nombre de la Categoría</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('nombre_categoria', null, array('placeholder' => 'Vinil','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-4 padding-bottom-30">
                        <div class="row">
                            <button type="submit" class="btn btn-primary">Agregar</button>
                             <a type="button" class="btn" href="{{ URL::route('categorias.index') }}">Cancelar</a>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </section>
@endsection