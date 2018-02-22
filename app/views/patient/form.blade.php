<?php 
	EasyRow::$global_label_container = "<label class='control-label col-sm-2'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
	if(isset($patient)){
		Easy::$global_default_object = $patient;
	}
	$config = FacilityConfig::current();

?>

@section('content')
	{{Form::open(['class'=>'form-horizontal','ng:app'=>'','ng:controller'=>'PatientFormController'])}}
	<div class="row">
		<span class="pull-right">
			{{Form::submit((isset($patient) ? (isset($inline) ? 'Update and Select Patient' : 'Update Patient') : (isset($inline) ? 'Save and Select Patient' : "Create Patient")),['class' =>'btn btn-warning'])}}
			{{Form::reset('Clear Form',['class' =>'btn btn-danger'])}}
			<a class="btn btn-success" href="{{URL::to((isset($inline) ? 'BloodRequest':'').'Patient')}}"><span class='glyphicon glyphicon-arrow-left'></span> Go Back to Patients</a>
			@if(isset($inline))
			<a class="btn btn-info" href="#" ng:click="cancelPatientSelect()"><span class='glyphicon glyphicon-arrow-left'></span> Blood Request</a>
			@endif
		</span>
		
	</div>
	<div id="patient_form">
		<legend><div class="col-sm-1"></div>
		<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> {{(isset($patient) ? 'Update Patient Record' : 'New Patient')}}</div>
		</legend>

			@if($config != null)
				@if($config->auto_patient_id == 'N')
					{{EasyRow::make('Hospital Record No.',[
						EasyText::make('patient_id',['placeholder' => 'Hospital Record No.'],'Hospital Record No.')
					])->render()}}
				@endif
			@endif
		
			<!-- Patient Name -->
			{{EasyRow::make('Patient Name',[
				EasyText::make('fname',['placeholder' => 'First'],'First Name'),
				EasyText::make('mname',['placeholder' => 'Middle']),
				EasyText::make('lname',['placeholder' => 'Last'],'Last Name')
			])->render()}}

			<!-- Name Suffix And Gender-->
			{{EasyRow::make('Name Suffix',[
				EasyText::make('name_suffix')

			])->render()}}

			<!-- Date of Birth And Civil Status -->
			{{EasyRow::make('Date of Birth',[
				EasyDate::make('bdate',[],'Date of Birth'),
				Form::label('','Gender',['class' => 'control-label col-sm-2']),
				EasyRadioButton::make('gender',['items' => ['M' => 'Male' , 'F' => 'Female'],'container_class' => 'radio-inline has-tooltip'],'Gender')
			])->render()}}

			<div id="addressToggleContainer">

			


			{{EasyRow::make('',[
				'<a href="#" id="addressToggle" style="margin-left:1em;" class="" > -- Show More --  </a>'
			])->render()}}
			</div>
			
			
			<div id="address" style="display:none;">
			<!-- Nationality -->
			{{EasyRow::make('Nationality',[
				EasySelect::make('nationality',['items' => $nationalities, 'parent_class' => 'col-sm-2']),
				Form::label('','Civil Status',['class' => 'control-label col-sm-2']),
				EasySelect::make('civil_stat',['items' => ['S' => 'Single','M' => 'Married', 'W' => 'Widowed', 'SP' => 'Separated']])
			])->render()}}			


			<!-- No., St., Block -->
			{{EasyRow::make('No., St., Block',[
				EasyText::make('no_st_blk',['parent_class' => 'col-sm-6'])
			])->render()}}

			<!-- Region And Tel No.-->
			{{EasyRow::make('Region',[
				EasySelect::make('regcode',['id' => 'regcode']),
				Form::label('','Tel no.',['class' => 'control-label col-sm-2']),
				EasyText::make('tel_no')
			])->render()}}

			<!-- Province And Mobile No.-->
			{{EasyRow::make('Province',[
				EasySelect::make('provcode',['id' => 'provcode']),
				Form::label('','Mobile no.',['class' => 'control-label col-sm-2']),
				EasyText::make('mobile_no')
			])->render()}}

			<!-- City/Municipality And Fax No.-->
			{{EasyRow::make('City/Municipality',[
				EasySelect::make('citycode',['id' => 'citycode'])
				
			])->render()}}

			<!-- Barangay And Email-->
			{{EasyRow::make('Barangay',[
				EasySelect::make('bgycode',['id' => 'bgycode']),
				Form::label('','Email',['class' => 'control-label col-sm-2']),
				EasyText::make('email')
			])->render()}}
			</div>
			<hr/>
		
	</div>

	{{Form::close()}}

	{{HTML::script('js/lib/addressdropdown/addressdropdown.js')}}
	<script type="text/javascript">
	$(function(){
		$("[name='nationality']").find("option[value='137']").attr("selected","selected");
		@if(!isset($patient))
		AddressDropdown.config({server:'<?php echo URL::to('AddressDropdown') ?>'});
		@else
		AddressDropdown.config({
			server:'<?php echo URL::to('AddressDropdown') ?>',
			regcode:{{PrepareJSValue("regcode",$patient->regcode)}},
	        provcode:{{PrepareJSValue("provcode",$patient->provcode)}},
	        citycode:{{PrepareJSValue("citycode",$patient->citycode)}},
	        bgycode:{{PrepareJSValue("bgycode",$patient->bgycode)}}    
		});
		@endif
		AddressDropdown.implement();
		$("#addressToggle").click(function(){
			$("#address").show();
			$("#addressToggleContainer").hide();
		});
	});
	</script>

	@yield('form_script')
@stop