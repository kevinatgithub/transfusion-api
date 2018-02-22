@section('content')
	<style type="text/css">
		.census-header{
			background: #6b6b6b none repeat scroll 0 0;
			color:#fff;
			font-weight: bold;
		}
		.census-header-2{
			background: #e5a253;
			color:#fff;
			font-weight: bold;
		}
		.census-header-dark{
			background: #4b4b4b;
			color:#fff;
			font-weight: bold;
		}
		
	</style>
	<div class="row">
		<div class="col-xs-10 ">
			<legend><div ></div>
				<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Census Summary
				</div>
			</legend>

			<div id="actions" class="hidden">
				<a href="#" onclick="window.open('data:application/vnd.ms-excel,' + encodeURIComponent( $('div[id$=report]').html()));" class="pull-right"><span class="glyphicon glyphicon-export"></span> Export</a>
				<a href="#" onclick="$('#report').print();" class="pull-right" style="margin-right:1em;"><span class="glyphicon glyphicon-print"></span> Print</a>
			</div>
		</div>
	</div>
	
	<div id="report">
		

		<div class="row">
			<div class="col-xs-10 ">
				<small>Report Month <b style="margin-left:0.5em;">{{ReportController::dateFilterForHuman()}}</b></small>
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-xs-10 ">
				@include('reports.census')
			</div>
		</div>

		<div class="row">
			<div class="col-xs-10 ">
				@include('reports.in_patient')
			</div>
		</div>
			
		<div class="row">
			<div class="col-xs-10 ">

				@include('reports.out_patient')
			</div>
		</div>

		<!-- <div class="row">
			<div class="col-xs-5 col-xs-offset-1">
				@include('reports.sold_components')
				
			</div>
			<div class="col-xs-5">
				@include('reports.redeemed_components')
				
			</div>
		</div>

		<div class="row">
			<div class="col-xs-10 col-xs-offset-1">			
				@include('reports.outsource_tmc')
			</div>
		</div> -->
	</div>

	

	<script type="text/javascript">
		$(function(){


			loadDataRow(10,function(){
				loadDataRow(20,function(){
					loadDataRow(30,function(){
						loadDataRow(40,function(){
							loadDataRow(50,function(){
								computeTotals();
								$(".report-cell,.in-patient-row-sum,.in-patient-sum,.out-patient-row-sum,.out-patient-sum").each(function(){
									if($(this).text() == '0'){
										$(this).addClass('not-important');
									}
								});
								request = $.ajax({
									url : "{{url('Census/report2')}}"
								});
								request.success(function(data){
									in_patient = 0;
									for(component_cd in data.I){
										d = data.I[component_cd];
										$(".in-patient-row-sum[type='RH-'][component-cd='"+component_cd+"']").text(d);
										in_patient += d;
									}
									$(".in-patient-sum[type='RH-']").text(in_patient);
									out_patient = 0;
									for(component_cd in data.O){
										d = data.O[component_cd];
										$(".out-patient-row-sum[type='RH-'][component-cd='"+component_cd+"']").text(d);
										out_patient += d;
									}
									$(".out-patient-sum[type='RH-']").text(out_patient);

									$("#actions").removeClass('hidden');
								});
							});
						});
					});
				});
			});
		});

		function computeTotals(){

			// Row Summation for Crossmatched
			$(".in-patient-row-sum[type='XM'],.out-patient-row-sum[type='XM']").each(function(){
				sum = 0;
				$(this).parents('tr:first').find(".report-cell[shift='D'],.report-cell[shift='A'],.report-cell[shift='N']").each(function(){
					sum += $(this).text()*1;
				});
				$(this).text(sum);
			});

			// Row Summation for Transfused
			$(".in-patient-row-sum[type='TRANS'],.out-patient-row-sum[type='TRANS']").each(function(){
				sum = 0;
				$(this).parents('tr:first').find(".report-cell[shift='TRANS']").each(function(){
					sum += $(this).text()*1;
				});
				$(this).text(sum);
			});
			
			// Column Summation for Crossmatched
			in_patient_xm = 0;
			$(".in-patient-row-sum[type='XM']").each(function(){
				in_patient_xm += $(this).text()*1;
			});
			$(".in-patient-sum[type='XM']").text(in_patient_xm);
			out_patient_xm = 0;
			$(".out-patient-row-sum[type='XM']").each(function(){
				out_patient_xm += $(this).text()*1;
			});
			$(".out-patient-sum[type='XM']").text(out_patient_xm);

			// Column Summation for Transfused
			in_patient_trans = 0;
			$(".in-patient-row-sum[type='TRANS']").each(function(){
				in_patient_trans += $(this).text()*1;
			});
			$(".in-patient-sum[type='TRANS']").text(in_patient_trans);
			out_patient_trans = 0;
			$(".out-patient-row-sum[type='TRANS']").each(function(){
				out_patient_trans += $(this).text()*1;
			});
			$(".out-patient-sum[type='TRANS']").text(out_patient_trans);

			// Census general summary
			$(".census-sum[patient-care='I'][type='XM']").text(in_patient_xm);
			$(".census-sum[patient-care='I'][type='TRANS']").text(in_patient_trans);
			$(".census-sum[patient-care='O'][type='XM']").text(out_patient_xm);
			$(".census-sum[patient-care='O'][type='TRANS']").text(out_patient_trans);
			$(".census-sum[patient-care='T'][type='XM']").text(in_patient_xm+out_patient_xm);
			$(".census-sum[patient-care='T'][type='TRANS']").text(in_patient_trans+out_patient_trans);

		}

		function loadDataRow(component_cd,cb){
			this.component_cd = component_cd;
			loadData(component_cd,"O").success(function(){
				loadData(component_cd,"A").success(function(){
					loadData(component_cd,"B").success(function(){
						loadData(component_cd,"AB").success(function(){
							cb();
						});
					});
				});
			});

			callMeBackBaby = function(cb){
				cb();
			}
			return this;
		}

		function loadData(component_cd,blood_type){

			$(".report-cell[component-cd='"+component_cd+"'][blood-type='"+blood_type+"']").html("<img src='{{url('loading.gif')}}' width='15'>");
			var request = $.ajax({
				url : "{{url('Census/report1')}}/"+component_cd+"/"+blood_type,
				type : "GET"
			});
			request.success(function(data){
				$("td[patient-care='I'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='D']").text(data.I.D);
				$("td[patient-care='I'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='A']").text(data.I.A);
				$("td[patient-care='I'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='N']").text(data.I.N);
				$("td[patient-care='I'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='TRANS']").text(data.I.T);

				$("td[patient-care='O'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='D']").text(data.O.D);
				$("td[patient-care='O'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='A']").text(data.O.A);
				$("td[patient-care='O'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='N']").text(data.O.N);
				$("td[patient-care='O'][component-cd='"+component_cd+"'][blood-type='"+blood_type+"'][shift='TRANS']").text(data.O.T);
			});
			return request;
		}
	</script>
@stop