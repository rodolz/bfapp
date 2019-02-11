@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Productos Disponibles</h2>
    @endsection
    
    @section('panel-title')
       <h2 class="title pull-left">Lista de Precios</h2>
    @endsection

    @section('add-styles')
        <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}" type="text/css"/>
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
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="row text-center">
                    {!! Form::open(array('id' => 'pago_form', 'method'=>'POST','class' => 'form-inline', 'route' => 'ventas.lista_precios_pdf')) !!}
                        {!! Form::select('categorias[]', $categorias, null, ['multiple' => true, 'id' => 'categorias', 'class' => 'form-control col-md-6']) !!}
                        <button type="submit" class="btn btn-purple right15">Exportar en PDF</button>
                    {!! Form::close() !!}
                    </div>
                </div>
            </div>
            <div class="row top15">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Código</th>
                                <!-- <th>Categoria</th> -->
                                <th>Descripción</th>
                                <th>Medidas</th>
                                <th scope="row">Cantidad Disp.</th>
                                <th>Precio Unitario</th>
                            </tr>
                        </thead>
                            <tbody>
                            @if(count($productos) > 0)
                                @php ($cond = '')
                                @foreach ($productos as $producto)
                                @if($producto->categoria->nombre_categoria != $cond)
                                <tr class='bg-primary'>
                                    <th colspan="5" class="text-center">{{ $producto->categoria->nombre_categoria }}</th>
                                </tr>

                                @php ($cond = $producto->categoria->nombre_categoria)
                                @endif
                                    <tr>
                                        <td>{{ $producto->codigo }}</td>
                                        <!-- <td>{{ $producto->categoria->nombre_categoria }}</td> -->
                                        <td>{{ $producto->descripcion }}</td>
                                        <td>{{ $producto->medidas }}</td>
                                        <th scope="row">{{ $producto->cantidad }}</th>
                                        <td>${{ number_format($producto->precio, 2, '.', ',') }}</td>
                                    </tr>
                                @endforeach
                            @else
                            <tr>
                                <td colspan="5">
                                    <h2 class="bold text-danger text-center"><i class="fa fa-exclamation-circle" aria-hidden="true" style="font-size:30px"></i> Inventario Vacio</h2>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- PAGINACION -->
        </div>
    </section>
@endsection
@section('add-plugins')

        <!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
    <script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js') }}"></script>
 <script type="text/javascript">


    $(document).ready(function() {
        $('#categorias').multiselect({
            includeSelectAllOption: true,
            selectAllText: 'Seleccionar todas',
            selectAllValue: 'select-all-value',
            buttonText: function(options, select) {
                if (options.length === 0) {
                    return 'Seleccione...';
                }
                else if (options.length > 4) {
                    return 'Mas de 4 categorias seleccionadas';
                }
                 else {
                     var labels = [];
                     options.each(function() {
                         if ($(this).attr('label') !== undefined) {
                             labels.push($(this).attr('label'));
                         }
                         else {
                             labels.push($(this).html());
                         }
                     });
                     return labels.join(', ') + '';
                 }
            }
        });
    });
    </script> 
    <!-- JS NECESARIO PARA ORDENES - END --> 
@endsection