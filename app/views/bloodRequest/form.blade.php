<?php
	EasyRow::$global_label_container = "<label class='control-label col-sm-2'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
	if(isset($physician)){
		Easy::$global_default_object = $physician;
	}

	$blood_types = isset($blood_types) ? $blood_types : [];
	$components = isset($components) ? $components : [];
	$config = FacilityConfig::current();
?>

@section('content')
	<div ng-app='BloodRequest' ng-controller='BloodRequestController' ng-cloak id="BloodRequestForm">
		<div ng-show='formVisible'>
			{{Form::open(['class' => 'form-horizontal','style' => 'margin-top:1em;'])}}
			
			<!-- Form Buttons -->
			<div class="row">
				<span class="pull-right">
					{{Form::submit((isset($physician) ? 'Update Blood Request' : "Create Blood Request"),['class' =>'btn btn-warning'])}}
					{{Form::reset('Clear Form',['class' =>'btn btn-danger'])}}
					<a class="btn btn-success" href="{{URL::to('BloodRequest')}}"><span class='glyphicon glyphicon-arrow-left'></span> Go Back to List</a>
				</span>
			</div>
			
			<legend><div class="col-sm-1"></div>
				<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> {{(isset($request) ? 'Update Blood Request' : 'Create New Blood Request')}}</div>
			</legend>

			
			<!-- Patient ID -->
			{{EasyRow::make('Patient HRN',[
				EasyTextLookUp::make('patient_id',['placeholder' => 'Hospital Record No','readonly' => 'readonly','ng:model' => 'patient.patient_id','container_class'=>'input-group col-sm-2',
				'btn_attr' => ['ng-click' => "show(2)", 'class' => 'btn btn-info btn-sm has-tooltip','title' => 'Click to select Patient']],'Patient HRN')
			])->render()}}
			

			<!-- Patient Name -->
			{{EasyRow::make('Patient Name',[
				EasyText::make('fname',['placeholder'=>'First','readonly' => 'readonly','ng:model' => 'patient.fname']),
				EasyText::make('mname',['placeholder'=>'Middle','readonly' => 'readonly' , 'ng:model' => 'patient.mname']),
				EasyText::make('lname',['placeholder'=>'Last','readonly' => 'readonly' , 'ng:model' => 'patient.lname'])
			])->render()}}

			<!-- Name Suffix and Gender -->
			{{EasyRow::make('Name Suffix',[
				EasyText::make('name_suffix',['placeholder'=>'Name Suffix','readonly' => 'readonly' , 'ng:model' => 'patient.name_suffix']),
				'<label class="control-label col-sm-2">Gender</label>',
				EasyRadioButton::make('gender',
					['items' =>['M' => 'Male', 'F' => 'Female'],
					 'container_class' => 'radio-inline has-tooltip',
					 'readonly' => 'readonly' , 
					 'items_attr' => "ng:model = 'patient.gender'"])
			])->render()}}

			<!-- Ward No. -->
			@if($config->enable_patient_ward_no == 'Y')
				{{EasyRow::make('Ward No.',[
					EasyText::make('ward_no',['placeholder'=>'Ward No.'],'Ward No.')
				])->render()}}
			@endif

			<!-- Room No. -->
			@if($config->enable_patient_room_no == 'Y')
				{{EasyRow::make('Room No.',[
					EasyText::make('room_no',['placeholder'=>'Room No.'],'Room No.')
				])->render()}}
			@endif

			<!-- Bed No. -->
			@if($config->enable_patient_bed_no == 'Y')
				{{EasyRow::make('Bed No.',[
					EasyText::make('bed_no',['placeholder'=>'Bed No.'],'Bed No.')
				])->render()}}
			@endif

			<!-- Patient Care -->
			{{EasyRow::make('Patient Care',[
				EasyRadioButton::make('patient_care',[
					'items' => ['O' => 'Outpatient', 'I' => 'Inpatient'],
					'container_class' => 'radio-inline has-tooltip',
					'items_attr' => "ng:model = 'patient_care'"
				],'Patient Care')
			])->render()}}

			<!-- Diagnosis -->
			{{EasyRow::make('Diagnosis',[
				EasyTextArea::make('diagnosis',['placeholder'=>'Diagnosis','parent_class'=>'col-sm-6','rows' => '4'],'Diagnosis')
			])->render()}}

			<!-- Attending Physician -->
			{{EasyText::make('physician_id',['ng:model' => 'physician.physician_id','style'=>'display:none;'])->render()}}
			{{EasyRow::make('Attending Physician',[
				EasyTextLookUp::make('physician_name',['parent_class' => 'col-sm-6','ng:model' => 'attending_physician','placeholder'=>'Attending Physician', 'readonly' => 'readonly',
				'btn_attr' => ['class' => 'btn btn-info btn-sm has-tooltip','title' => 'Click to select Attending Physician' , 'ng:click' => "show(3)"]],'Attending Physician'),
			])->render()}}
			
			<!-- Latest Hemoglobin Level -->
			{{EasyRow::make('Latest Hemoglobin Level',[
				EasyText::make('hemo_level',['placeholder' => 'Latest Hemoglobin Level'],'Latest Hemoglobin Level')
			])->render()}}

			<!-- Blood Type -->
			{{EasyRow::make('Blood Type',[
				EasySelect::make('blood_type',['items' => $blood_types],'Blood Type')
			])->render()}}
			
			@if(isset($bloodRequestDetailsError))
				@if($bloodRequestDetailsError != null)
					<div class="row">
						<div class="col-sm-2"></div>
						<div class="col-sm-6">
							<b class="text-danger">
								{{$bloodRequestDetailsError}}
							</b>
						</div>
					</div>
					<br/>
				@endif
			@endif
			<!-- Blood Components -->
			<div class="col-sm-2"></div>
			<div class="table-responsive col-sm-5">
				<table class="table table-bordered table-sm table-striped">
					<tr>
						<th>Blood Component</th>
						<th>Quantity</th>
					</tr>
					@foreach($components as $cc => $cn)
						<tr>
							<td><p class="pull-right">{{$cn}} <input type="checkbox" name="component[]" id="component_{{$cc}}" ng-model="components.{{$cc}}" /></p></td>
							<td>
								{{EasyText::make('quantity['.$cc.']',['class'=>'quantity','placeholder'=>$cn,'disabled' => 'disabled','ng-disabled' => '!components.'.$cc,'title'=>'<%quantity['.$cc.'].title%>'],null,'<%quantity['.$cc.'].value%>')->render()}}
								<span class="glyphicon glyphicon-warning-sign pull-right text-danger" style="margin-top:-1.8em;margin-right:0.5em;" ng:show="quantity[{{$cc}}].has_error"></span>
							</td>
						</tr>
					@endforeach
				</table>
			</div>
			
			{{Form::close()}}

		</div>

		<iframe ng-show="patientFrameVisible" src="{{URL::to('BloodRequestPatient/list')}}" style="border:none;width:100%;height:1000px;" id="patient_frame"></iframe>
		<iframe ng-show="physicianFrameVisible" src="{{URL::to('BloodRequestPhysician/list')}}" style="border:none;width:100%;height:1000px;" id="physician_frame"></iframe>
	
	</div>

	<script type="text/javascript">
		angular.module('BloodRequest',[],function($interpolateProvider){
			$interpolateProvider.startSymbol('<%');
		    $interpolateProvider.endSymbol('%>');
		});

		function BloodRequestController($scope){
			$scope.formVisible = true;
			$scope.patientFrameVisible = false;
			$scope.physicianFrameVisible = false;
			$scope.patient = {{json_encode(Input::all())}};
			$scope.patient_care = '{{Input::get('patient_care')}}';
			$scope.physician = {
				physician_id : '{{Input::get('physician_id')}}'
			};
			$scope.attending_physician = '{{Input::get('physician_name')}}';
			$scope.quantity = [];
			$scope.components = {
				@foreach($components as $cc => $cn)
					"{{$cc}}" : false,
				@endforeach
			};
			@if(array_key_exists('quantity',Input::all()))
				//$scope.quantity = {{json_encode(Input::get('quantity'))}};
				@foreach(Input::get('quantity') as $cc => $q)
					$scope.quantity[{{$cc}}] = {
						value : '{{$q}}'
					};
					$scope.components[{{$cc}}] = true;
					@if($q == '' || $q == 0)
						$scope.quantity[{{$cc}}].title = 'Please enter quantity';
						$scope.quantity[{{$cc}}].has_error = true;
					@elseif(!is_numeric($q))
						alert('asd');
						$scope.quantity[{{$cc}}].title = 'Please enter valid numeric value';
						$scope.quantity[{{$cc}}].has_error = true;
					@endif
				@endforeach
			@endif
			$scope.show = function(frame){
				if(frame == 2){
					$scope.formVisible = false;
					$scope.patientFrameVisible = true;
					$scope.physicianFrameVisible = false;
				}else if(frame == 1){
					$scope.formVisible = true;
					$scope.patientFrameVisible = false;
					$scope.physicianFrameVisible = false;
				}else if(frame == 3){
					$scope.formVisible = false;
					$scope.patientFrameVisible = false;
					$scope.physicianFrameVisible = true;
				}

			}

			window.get = function(name,data){
				 $scope.$apply(function(){
		            $scope[name] = data;
		         });
			}


		}

		window.reloadPatientFrame = function(){
			$("#patient_frame").attr("src","{{URL::to('BloodRequestPatient/list')}}");
		}

		window.reloadPhysicianFrame = function(){
			$("#physician_frame").attr("src","{{URL::to('BloodRequestPhysician/list')}}");
		}

		$(".quantity").mask('000');
	</script>

	<!-- {{HTML::script('js/AngularModules.js')}}
	{{HTML::script('js/controller/Controllers.js')}} -->
@stop