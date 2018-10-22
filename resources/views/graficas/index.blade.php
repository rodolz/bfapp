@extends('layout.master')
	
	@section('page-title')
        <h2 class="title bold">Graficas</h2>
    @endsection

    @section('content')
		<section class="box">
		        <div class="content-body">  
		            <div class="row">
		                <div class="col-md-12 col-sm-12 col-xs-12">
		                    <div class="row">
		                    <canvas id="projects-graph" width="1000" height="400"></canvas>
		                    </div>
		             	</div>
	             	</div>
             	</div>
     	</section>
    @endsection	

    @section('add-plugins')
    <script src="{{ asset('assets/plugins/chartjs-chart/Chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/chart-chartjs.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
	    $(function(){
		  $.getJSON("charts/productos", function (result) {

		    var labels = [],data=[];
		    for (var i = 0; i < result.length; i++) {
		        labels.push(result[i].month);
		        data.push(result[i].projects);
		    }

		    var buyerData = {
		      labels : labels,
		      datasets : [
		        {
		          fillColor : "rgba(240, 127, 110, 0.3)",
		          strokeColor : "#f56954",
		          pointColor : "#A62121",
		          pointStrokeColor : "#741F1F",
		          data : data
		        }
		      ]
		    };
		    var buyers = document.getElementById('projects-graph').getContext('3d');
		    
			var chartInstance = new Chart(buyers, {
			    type: 'line',
			    data: buyerData,
			});
		  });
		});
 	</script>
    @endsection