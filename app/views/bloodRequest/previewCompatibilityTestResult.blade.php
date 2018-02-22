<?php
	$config = FacilityConfig::current();
	$user = User::current();
	$facility = Facility::find($user->facility_cd);
	
	EasyRow::$global_label_container = "<label class='control-label col-sm-3'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip','parent_class'=>'col-sm-9']; 
	Easy::$global_default_object = $detail;
	$config = FacilityConfig::current();
?>
@section('content')
<style>
#paper{
	width:1000px;
	font-size:16px;
	/*border:1px solid #000;*/
	color:#a1a1a1;
	padding:0;
	padding-left:20px;
	padding-right: 20px;
	font-family: 'Times new roman';
	position:relative;
}
#paper h2,p{
	width:100%;
	text-align: center;
}
#paper table{
	width:90%;
	font-size: 16px;
	border-color: #a1a1a1;
}
#paper table th{
	text-align: left;
}
.unit td,.unit th{
	border:1px solid #a1a1a1;
}
.box{
	float: left;
	width:	16px;
	height: 16px;
	border: 1px solid #a1a1a1;
	margin: 5px;
	margin-top:1px;
}
.box-sm{
	float:left;
	width: 15px;
	height: 15px;
	border: 1px solid #a1a1a1;
	margin: 5px;
	margin-top: 3px;
}
</style>
{{ HTML::style('css/bootstrap.css') }}
{{ HTML::style('css/bootstrap-theme.css') }}
<div ng:app="Preview" ng:controller="PreviewCtrl">
	<div id="paper" class="col-xs-9 pull-right">
		<h2>{{$facility->facility_name}}</h2>
		<p>Where Patients are Partners</p>
		<p>
			<b>BLOOD BANK AND TRANSFUSION SERVICES</b><br/>
			<b>COMPATIBILITY TEST RESULT</b>
		</p>
		<table style="margin-left:auto;margin-right:auto;" border="0" cellpadding="0" cellspacing="0">
			<tr style="font-size:22px;">
				<td width="120">NAME: </td>
				<td colspan="5"><b>{{$bloodRequest->patient->getFullName()}}</b></td>
			</tr>
			<tr>
				<td>PIN: </td>
				<td colspan="3">{{$bloodRequest->patient->patient_id}}</td>
				<td width="150">XMATCH CS#</td>
				<td><%crossmatch_cs_no%></td>
			</tr>
			<tr>
				<td>DATE OF BIRTH:</td>
				<td colspan="3">{{date('d-M-y',strtotime($bloodRequest->patient->bdate))}}</td>
				<td>SPECIMEN NO.</td>
				<td><%specimen_no%></td>
			</tr>
			<tr>
				<td>AGE/SEX:</td>
				<td width="200"><span id="age"></span>/<span>{{($bloodRequest->patient->gender == 'M' ? 'Male' : ($bloodRequest->patient->gender == 'F' ? 'Female' : null))}}</span></td>
				<td width="80"></td>
				<td width="150"></td>
				<td>DATE REQUESTED:</td>
				<td>{{date('d-M-y H:i:s',strtotime($bloodRequest->created_dt))}}</td>
			</tr>
			<tr>
				<td>Doctor:</td>
				<td colspan="3">{{$bloodRequest->physician->getFullName()}}</td>
				<td>DATE PERFORMED:</td>
				<td>{{date('d-M-y H:i:s',strtotime($detail->crossmatch_dt))}}</td>
			</tr>
			<tr>
				<td>ROOM NO:</td>
				<td colspan="5">{{$bloodRequest->room_no}}</td>
			</tr>
		</table>

		<table style="margin-left:auto;margin-right:auto;" border="1" cellpadding="0" cellspacing="0" bordercolor="#a1a1a1">
			<tr style="height:40px;">
				<th style="text-align:center;" width="38%">PATIENT/DONOR DATA</th>
				<th style="text-align:center;" width="34%">CROSS MATCHING RESULTS</th>
				<th style="text-align:center;" width="28%">RELEASE OF BLOOD</th>
			</tr>
			<tr>
				<td style="padding:5px;" valign="top">
					<table cellpadding="0" cellspacing="0" style="width:100%;">
						<tr>
							<td width="140">PATIENT</td>
							<td width="80" align="right">ABO Group</td>
							<td style="font-size:18px;" align="center">{{$bloodRequest->bloodType->abo_grp}}</td>
						</tr>
						<tr>
							<td></td>
							<td align="right">Rh type</td>
							<td style="font-size:18px;" align="center">{{($bloodRequest->bloodType->rh_type == 'pos' ? 'Positive' : ($bloodRequest->bloodType->rh_type == 'neg' ? 'Negative' : null))}}</td>
						</tr>
						<tr>
							<td valign="top" colspan="2">Antibody Screening</td>
							<td valign="top" align="center">
								<span ng:show="unit_antibody_screening == 'P'">Positive</span>
								<span ng:show="unit_antibody_screening == 'N'">Negative</span>
							</td>
						</tr>
						<tr>
							<td colspan="3">DONATION ID</td>
						</tr>
						<tr style="font-size:20px;">
							<td colspan="3">
								<b>{{$detail->donation_id}}</b>
								&nbsp;
							</td>
						</tr>
						<tr>
							<td></td>
							<td>ABO group</td>
							<td style="font-size:18px;">{{$bloodRequest->bloodType->abo_grp}}</td>
						</tr>
						<tr>
							<td></td>
							<td>Rh Type</td>
							<td style="font-size:18px;">{{($bloodRequest->bloodType->rh_type == 'pos' ? 'Positive' : ($bloodRequest->bloodType->rh_type == 'neg' ? 'Negative' : null))}}</td>
						</tr>
						<tr>
							<td colspan="3">&nbsp;</td>
						</tr>
						<tr ng:show="nat != ''">
							<td>NAT</td>
							<td colspan="2" style="padding-left:10px;"><%nat%></td>
						</tr>
						<tr>
							<td>HBsAg</td>
							<td colspan="2" style="padding-left:10px;">NON-REACTIVE</td>
						</tr>
						<tr>
							<td>Syphilis</td>
							<td colspan="2" style="padding-left:10px;">NON-REACTIVE</td>
						</tr>
						<tr>
							<td>Anti-HIV</td>
							<td colspan="2" style="padding-left:10px;">NON-REACTIVE</td>
						</tr>
						<tr>
							<td>Anti-HCV</td>
							<td colspan="2" style="padding-left:10px;">NON-REACTIVE</td>
						</tr>
						<tr>
							<td>Malarial Smear</td>
							<td colspan="2" style="padding-left:10px;">No Malarial Parasite Seen</td>
						</tr>
						<tr>
							<td>Antibody Screening</td>
							<td colspan="2" style="padding-left:10px;">
							<span ng:show="patient_antibody_screening == 'P'">Positive</span>
							<span ng:show="patient_antibody_screening == 'N'">Negative</span>
							</td>
						</tr>
					</table>
				</td>
				<td style="padding:5px;" valign="top">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2"><b>Major</b></td>
						</tr>
						<tr>
							<td width="100" style="padding-left:15px;">Saline Phase</td><td><label><div class="box-sm"></div> No agglutination</label></td>
						</tr>
						<tr>
							<td></td><td><label><div class="box-sm"></div> With agglutination</label></td>
						</tr>
						<tr>
							<td style="padding-left:15px;">LISS phase</td><td><label><div class="box-sm"></div> No agglutination</label></td>
						</tr>
						<tr>
							<td></td><td><label><div class="box-sm"></div> With agglutination</label></td>
						</tr>
						<tr>
							<td style="padding-left:15px;">AHG phase</td><td><label><div class="box-sm"></div> No agglutination</label></td>
						</tr>
						<tr>
							<td></td><td><label><div class="box-sm"></div> With agglutination</label></td>
						</tr>
						<tr>
							<td colspan="2">&nbsp;</td>
						</tr>
						<tr>
							<td colspan="2"><b>Minor</b></td>
						</tr>
						<tr>
							<td style="padding-left:15px;">Saline phase</td><td><label><div class="box-sm"></div> No agglutination</label></td>
						</tr>
						<tr>
							<td></td><td><label><div class="box-sm"></div> With agglutination</label></td>
						</tr>
					</table>
				</td>
				<td style="padding: 5px 10px;" valign="top">
					<table cellpadding="0" cellspacing="0">
						<tr>
							<td colspan="2">Form of blood released</td>
						</tr>
						<tr>
							<td colspan="2">
								<b style="font-size:18px;">{{$detail->component->comp_name}}</b>
								<span ng:show="leuko"><br/>Leukoreduced/Leukodepleted</span>
								<span ng:show="irradiated"><br/>Irradiated</span>
								<span ng:show="washed"><br/>Washed</span>
							</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr>
							<td colspan="2">Amount Released</td>
						</tr>
						<tr>
							<td width="150">1 UNIT</td><td> ml</td>
						</tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">Released by:</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">Receipt of blood:</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">Orderly</td></tr>
						<tr><td colspan="2">&nbsp;</td></tr>
						<tr><td colspan="2">Date/Time</td></tr>
					</table>
				</td>
			</tr>
		</table>
		
		<table style="margin-left:auto;margin-right:auto;" cellpadding="0" cellspacing="0">
			<tr>
				<td style="width:33%;">
					<table cellpadding="0" cellspacing="0" style="width:100%;">
						<tr><td width="130" valign="top">Remarks :</td><td valign="top" ng-bind-html="remark | breakFilter"></td></tr>
					</table>
				</td>
				<td style="width:33%;"></td>
				<td style="width:33%;"></td>
			</tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr><td colspan="3">&nbsp;</td></tr>
			<tr>
				<td style="text-align:center;"><%mt_name%></td>
				<td style="text-align:center;"><%mt2_name%></td>
				<td style="text-align:center;"><%bb_head%></td>
			</tr>
			<tr>
				<td style="text-align:center;"><%mt_position%></td>
				<td style="text-align:center;">Medical Technologist</td>
				<td style="text-align:center;"><%bb_head_position%></td>
			</tr>
		</table>

		<div style="border:1px solid #a1a1a1;margin-left:auto;margin-right:auto;width:90%;font-size:16px;font-weight:bold;padding:10px;">
			Check list prior to transfusion: &nbsp;&nbsp;&nbsp;(To be filled-up by Nurse In-Charge)
			<ul style="list-style:none;">
				<li><div class="box"></div> Is the name of patient same with compatibility result form?</li>
				<li><div class="box"></div> Is blood type and Rh type of patient same with the blood bag unit label?</li>
				<li><div class="box"></div> Is donor unit serial number same with the compatibility result form?</li>
				<li><div class="box"></div> Is the expiration date of blood checked?</li>
				<li><div class="box"></div> Is the blood product condition checked?</li>
			</ul>
			<br/>
			<span style="margin-left:50px;">Accomplished by:__________________, R.N.</span><span style="margin-left:50px;">Date and Time:__________</span>
			<br/>
			<span style="margin-left:50px;">Counter-checked by:_______________</span><span style="margin-left:98px;">Date and Time:__________</span>
		</div>

		<div style="margin-left:auto;margin-right:auto;width:90%;font-size:16px;font-weight:bold;padding:10px;">
			<u>IMPORTANT NOTICE!</u>
			<ol>
				<li style="margin-left:50px;">DO NOT WARM blood units unless rapid transfusion is indicated in massive bleeding.</li>
				<li style="margin-left:50px;">If there is ANY DELAY in transfusion return this unit within 30 minutes to prevent spoilage.</li>
				<li style="margin-left:50px;">Your signature shall mean acceptance of responsibility as custodian of released blood unit/s</li>
				<li style="margin-left:50px;">INFUSE WITHIN 4 HOURS UPON PUNCTURE OF BLOOD</li>
			</ol>
		</div>
	</div>







	<div id="form" class="col-xs-3 pull-left bg-info" style="height:1200px;">
		<form class="form-horizontal " style="margin-top:20px;" method="post">
			<div ng:hide="true">
			<i class="text-info">Patient Antibody Screening</i>
			{{EasyRow::make('P.A.S.',[
				EasySelect::make('patient_antibody_screening',[
					'items' => [''=>'','P' => 'Positive', 'N' => 'Negative'],
					'ng:model' => 'patient_antibody_screening'
				])
			])->render()}}
			</div>
			
			<i class="text-info">Blood Unit Antibody Screening</i>
			{{EasyRow::make('B.A.S.',[
				EasySelect::make('unit_antibody_screening',[
					'items' => ['' => '', 'P' => 'Positive', 'N' => 'Negative'],
					'ng:model' => 'unit_antibody_screening'
				])
			])->render()}}

			{{EasyRow::make('NAT',[
				EasyText::make('nat',['ng:model' => 'nat'])
			])->render()}}

			<i class="text-info">Blood Unit Remarks</i>
			<label>
				<input type="checkbox" name="leuko" id="leuko" value="leuko" ng:model="leuko"></input> Leukoreduced / Leukodepleted
			</label>
			<br/>
			<label>
				<input type="checkbox" name="irradiated" id="irradiated" value="irradiated" ng:model="irradiated"></input> Irradiated
			</label>
			<br/>
			<label>
				<input type="checkbox" name="washed" id="washed" value="washed" ng:model="washed"></input> Washed
			</label>


			<legend></legend>
			
			<i class="text-info">Blood Bank Head</i>
			{{EasyRow::make('Head',[
				EasyText::make('bb_head',['placeholder'=>'Head','ng:model' => 'bb_head'])
			])->render()}}

			{{EasyRow::make('Position',[
				EasyText::make('bb_head_position',['placeholder'=>'Position','ng:model' => 'bb_head_position'])
			])->render()}}

			<i class="text-info">Medical Technologist</i>
			{{EasyRow::make('Medtech',[
				EasyText::make('mt_name',['placeholder'=>'Medtech','ng:model' => 'mt_name'])
			])->render()}}

			{{EasyRow::make('',[
				EasyText::make('mt2_name',['placeholder'=>'Medtech','ng:model' => 'mt2_name'])
			])->render()}}

			<div ng:hide="true">
			{{EasyRow::make('Position',[
				EasyText::make('mt_position',['placeholder'=>'Position','ng:model' => 'mt_position'])
			])->render()}}
			</div>
			<legend></legend>

			{{EasyRow::make('XMATCH CS#',[
				EasyText::make('crossmatch_cs_no',['placeholder'=>'XMATCH CS#','ng:model' => 'crossmatch_cs_no'])
			])->render()}}

			{{EasyRow::make('Specimen No.',[
				EasyText::make('specimen_no',['placeholder'=>'Specimen No.','ng:model' => 'specimen_no'])
			])->render()}}

			{{EasyRow::make('Remarks',[
				EasyTextArea::make('remark',['placeholder'=>'Remarks','rows' => '2','ng:model' => 'remark'])
			])->render()}}


			<input class="btn btn-success pull-right" type="submit" value="Save & Print" onclick="$('form').submit()"></input>
		</form>
		
	</div>
</div>   
<script type="text/javascript">
	var sce;
	$(function(){
		var m = '{{date("m",strtotime($bloodRequest->patient->bdate))}}';
		var d = '{{date("d",strtotime($bloodRequest->patient->bdate))}}';
		var y = '{{date("Y",strtotime($bloodRequest->patient->bdate))}}';
		$("#age").html(calculate_age(m,d,y));
	});

	var preview = angular.module('Preview',[],function($interpolateProvider){
			$interpolateProvider.startSymbol('<%');
		    $interpolateProvider.endSymbol('%>');
		});

		preview.filter('breakFilter', function () {
		    return function (text) {
		        if (text !== undefined) return sce.trustAsHtml(text.replace(/\n/g, '<br />'));
		    };
		});

	function PreviewCtrl($scope,$sce){
		$scope.patient_antibody_screening = "N";
		$scope.unit_antibody_screening = {{json_encode($detail->unit_antibody_screening)}};
		$scope.nat = "{{$detail->nat != null ? $detail->nat : 'Reactive'}}";
		$scope.leuko = {{$detail->leuko == 1 ? 'true' : 'false'}};
		$scope.irradiated = {{$detail->irradiated == 1 ? 'true' : 'false'}};
		$scope.washed = {{$detail->washed == 1 ? 'true' : 'false'}};
		$scope.bb_head = "{{$detail->bb_head}}";
		$scope.bb_head_position = "{{$detail->bb_head_position}}";
		$scope.mt_name = "{{$detail->mt_name}}";
		$scope.mt2_name = "{{$detail->mt2_name}}";
		$scope.mt_position = "Medical Technologist";
		$scope.crossmatch_cs_no = "{{$detail->crossmatch_cs_no}}";
		$scope.specimen_no = "{{$detail->specimen_no}}";
		$scope.remark = {{json_encode($detail->remark)}};
		sce = $sce;
		// $scope.unit_antibody_screening = $sce.trustAsHtml($scope.unit_antibody_screening);
	}



</script>
@stop