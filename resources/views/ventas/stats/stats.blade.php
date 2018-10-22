@extends('layout.master')
    @section('page-title')
        <h2 class="title bold">Métricas <i class="fa fa-line-chart"></i> <small>{{$empresa}}</small></h2>
    @endsection

    @section('content')

        <div class="col-lg-12">
            <section class="box ">
                <div class="content-body">
                    <div class="row">
                        <div>{!! $chart->container() !!}</div>
                    </div>
                </div>
            </section>
        </div>

     @endsection  

@section('add-plugins')
<script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highcharts/6.0.6/highcharts.js" charset="utf-8"></script>
<script src=//cdn.jsdelivr.net/npm/fusioncharts@3.12.2/fusioncharts.js charset=utf-8></script>
<script src=//cdnjs.cloudflare.com/ajax/libs/echarts/4.0.2/echarts-en.min.js charset=utf-8></script>
     {!! $chart->script() !!}
@endsection