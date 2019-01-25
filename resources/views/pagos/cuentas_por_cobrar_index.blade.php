@extends('layout.master')

    @section('page-title')
        <h2 class="title bold">Pagos</h2>
    @endsection

    @section('panel-title')
       <h2 class="title pull-left">Cuentas por Cobrar</h2>
    @endsection

    @section('add-styles')
    <link rel="stylesheet" href="{{ asset('css/bootstrap-multiselect.css') }}" type="text/css"/>
    @endsection
    
@section('content')
    <section class="box primary">
        <header class="panel_header">
            @yield('panel-title')
        </header>
        <div class="content-body">    
        	<div class="row">
                <div class="well transparent col-md-12 col-sm-12 col-xs-12">
                    <!-- start -->
                    <div class="row">
                    	{!! Form::open(array('id' => 'cliente_form', 'action'=> 'PagosController@cuentas_por_cobrar', 'method'=>'POST','class' => 'form-inline')) !!}
                        <div class="col-md-12 col-sm-12 col-xs-12">
                        	<div class="form-group col-lg-4 col-md-4 col-sm-9 col-xs-12">
	                            <h2 class="bold">Clientes</h2><br/>
	                                Seleccione al cliente
	                                <div class="controls">
                                        {!! Form::select('clientes[]', $clientes, null, ['multiple' => true, 'id' => 'clientes', 'class' => 'form-control col-md-6']) !!}
	                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="clearfix"></div><br>

                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                            <button type="submit" target="_blank" class="btn btn-primary btn-md"><i class="fa fa-arrow-right"></i> Consultar </button>      
                        </div>
                    </div>
                    {!! Form::close() !!}


                    <!-- end -->


                </div>
            </div>
        </div>
    </section>
@endsection

@section('add-plugins')

<!-- OTHER SCRIPTS INCLUDED ON THIS PAGE - START --> 
<script type="text/javascript" src="{{ asset('js/bootstrap-multiselect.js') }}"></script>

<script type="text/javascript">
$(document).ready(function() {
    $('#clientes').multiselect({
        includeSelectAllOption: true,
        selectAllText: 'Seleccionar todas',
        selectAllValue: 'select-all-value',
        buttonText: function(options, select) {
            if (options.length === 0) {
                return 'Ningun cliente seleccionado...';
            }
            else if (options.length > 4) {
                return 'Mas de 4 clientes seleccionados';
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
@endsection
