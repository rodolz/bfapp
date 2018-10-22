@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Comisiones</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Modificando Comision #</h2>
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

	    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
   		 @endif

   		 {!! Form::model($producto, ['method' => 'PATCH','route' => ['clientes.update', $producto->id]]) !!}
            <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12">

                <div class="form-group">
                    <label class="form-label" for="field-1">Codigo</label>
                    <span class="desc"></span>
                    <div class="controls">
                        {!! Form::text('codigo', null, array('placeholder' => 'GS6014M','class' => 'form-control')) !!}
                    </div>
                </div>


                <div class="form-group">
                    <label class="form-label" for="field-1">Nombre del Producto</label>
                    <span class="desc"></span>
                    <div class="controls">
                        {!! Form::text('nombre_producto', null, array('placeholder' => 'Nombre del producto','class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="field-6">Descripcion</label>
                    <span class="desc"><i>'descripcion breve del producto'</i></span>
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
                    <label class="form-label" for="field-1">Precio</label>
                    <span class="desc"></span>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-usd"></i></span>
                        {!! Form::number('precio', null, array('class' => 'form-control', 'step'=>'any')) !!}
                    </div>
                </div>


                <div class="form-group">
                    <label class="form-label" for="field-1">Cantidad</label>
                    <span class="desc"></span>
                    <div class="controls">
                        {!! Form::number('cantidad', null, array('class' => 'form-control')) !!}
                    </div>
                </div>

                <div class="col-lg-8 col-md-8 col-sm-9 col-xs-12 padding-bottom-30">
                    <div class="text-left">
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                        <a type="button" class="btn" href="{{ URL::previous() }}">Cancelar</a>
                    </div>
                </div>
                {!! Form::close() !!}
    </div>

@endsection