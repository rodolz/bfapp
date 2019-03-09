@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Productos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Nuevo Producto</h2>
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
       		 {!! Form::open(array('route' => 'productos.store','method'=>'POST')) !!}
                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-2">
                    <div class="form-group">
                        <label class="form-label" for="field-1">Código</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('codigo', null, array('placeholder' => 'GS6014M','class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-1">Categoría</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::select('idCategoria', $categorias, null, ['placeholder' => 'Escojer...', 'class' => 'form-control']) !!}
                        </div>
                    </div>
            
                    <div class="form-group">
                        <label class="form-label" for="field-6">Descripción</label>
                        <span class="desc">"Descripcion breve del producto"</span>
                        <div class="controls">
                            {!! Form::textarea('descripcion', null, array('size' => '50x3', 'class' => 'form-control')) !!}
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
                        <label class="form-label" for="field-1">Medidas</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::text('medidas', null, array('placeholder' => '2.5m x 50m','class' => 'form-control')) !!}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="form-label" for="field-1">Precio Venta</label>
                        <span class="desc"></span>
                        <div class="input-group">
                        	<span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                            {!! Form::number('precio', null, array('class' => 'form-control', 'step'=>'any')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-1">Precio Costo</label>
                        <span class="desc"></span>
                        <div class="input-group">
                            <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                            {!! Form::number('precio_costo', null, array('class' => 'form-control', 'step'=>'any')) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="field-1">Cantidad</label>
                        <span class="desc"></span>
                        <div class="controls">
                            {!! Form::number('cantidad', null, array('class' => 'form-control')) !!}
                        </div>
                    </div>

                    <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 col-lg-offset-4 padding-bottom-30">
                        <div class="row">
                            <button type="submit" class="btn btn-primary ">Agregar</button>
                             <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </section>
@endsection