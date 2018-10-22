@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Proveedores</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo Proveedor</h2>
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
                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-2">
                        {!! Form::open(array('route' => 'proveedores.store','method'=>'POST')) !!}
                            <div class="form-group">
                                <label class="form-label" for="field-1">Nombre</label>
                                <span class="desc"></span>
                                <div class="controls">
                                    {!! Form::text('name', null, array('placeholder' => 'Nombre del Proveedor','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="field-1">Email</label>
                                <span class="desc"><i></i></span>
                                <div class="controls">
                                    {!! Form::text('email', null, array('placeholder' => 'nombre@email.com','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="field-1">Codigo Postal</label>
                                <span class="desc"></span>
                                <div class="controls">
                                    {!! Form::text('postcode', null, array('placeholder' => '40126','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="field-6">Website</label>
                                <span class="desc"></span>
                                <div class="controls">
                                    {!! Form::text('website', null, array('placeholder' => 'www.proveedor.com.pa','class' => 'form-control')) !!}
                                </div>
                            </div>
            <!--                 <div class="form-group">
                                <label class="form-label" for="field-1">Image</label>
                                <span class="desc"></span>
                                <div class="controls">
                                    <input type="file" class="form-control" id="field-5">
                                </div>
                            </div> -->

                            <div class="form-group">
                                <label class="form-label" for="field-6">Direccion</label>
                                <span class="desc"><i></i></span>
                                <div class="controls">
                                    {!! Form::textarea('address', null, array('size' => '50x3', 'class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="field-1">Ciudad</label>
                                <span class="desc"><i></i></span>
                                <div class="controls">
                                    {!! Form::text('city', null, array('placeholder' => 'Shanghai','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label" for="field-1">Pais</label>
                                <span class="desc"><i></i></span>
                                <div class="controls">
                                    {!! Form::text('country', null, array('placeholder' => 'China','class' => 'form-control')) !!}
                                </div>
                            </div>

                            <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-4 padding-bottom-30">
                                <div class="row">
                                    <button type="submit" class="btn btn-primary">Agregar</button>
                                     <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>

@endsection