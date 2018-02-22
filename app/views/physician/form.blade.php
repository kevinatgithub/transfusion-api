<?php 
	EasyRow::$global_label_container = "<label class='control-label col-sm-2'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
	if(isset($physician)){
		Easy::$global_default_object = $physician;
	}
?>

@section('content')
	{{Form::open(['class'=>'form-horizontal','ng:app'=>'','ng:controller'=>'PhysicianFormController'])}}
	<div class="row">
		<span class="pull-right">
			{{Form::submit((isset($physician) ? ((isset($inline) ? 'Update and Select Physician' : 'Update Physician')) : ((isset($inline) ? 'Save and Select Physician' : "Create Physician"))),['class' =>'btn btn-warning'])}}
			{{Form::reset('Clear Form',['class' =>'btn btn-danger'])}}
			<a class="btn btn-success" href="{{URL::to((isset($inline) ? 'BloodRequest':'').'Physician')}}"><span class='glyphicon glyphicon-arrow-left'></span> Go Back to Physician</a>
			@if(isset($inline))
				<a class="btn btn-info" href="#" ng:click="cancelPhysicianSelect()"><span class='glyphicon glyphicon-arrow-left'></span> Blood Request</a>
			@endif
		</span>
		
	</div>
	<div id="physician_form">
		<legend><div class="col-sm-1"></div>
		<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> {{(isset($physician) ? 'Update Physician Record' : 'New Physician')}}</div>
		</legend>

			<!-- Licence Number -->
			{{EasyRow::make('License Number',[
				EasyText::make('license_no',['placeholder' => 'License Number','parent_class' => 'col-sm-6'],'License Number')
			])->render()}}
			
			<!-- Physician Name -->
			{{EasyRow::make('Physician Name',[
				EasyText::make('fname',['placeholder' => 'First'],'First Name'),
				EasyText::make('mname',['placeholder' => 'Middle']),
				EasyText::make('lname',['placeholder' => 'Last'],'Last Name')
			])->render()}}

			<!-- Name Suffix And Gender-->
			{{EasyRow::make('Name Suffix',[
				EasyText::make('name_suffix'),
				Form::label('','Civil Status',['class' => 'control-label col-sm-2']),
				EasySelect::make('civil_stat',['items' => ['S' => 'Single','M' => 'Married', 'W' => 'Widowed', 'SP' => 'Separated']])

			])->render()}}

			<!-- Date of Birth And Civil Status -->
			{{EasyRow::make('Date of Birth',[
				EasyDate::make('bdate',[],'Date of Birth'),
				Form::label('','Gender',['class' => 'control-label col-sm-2']),
				EasyRadioButton::make('gender',['items' => ['M' => 'Male' , 'F' => 'Female'],'container_class' => 'radio-inline has-tooltip'],'Gender')
			])->render()}}

			<!-- Specialty -->
			{{EasyRow::make('Specialty',[
				EasyText::make('specialty',['placeholder' => 'Specialty','parent_class' => 'col-sm-6'],'Specialty')
			])->render()}}

			<!-- Nationality -->
			{{EasyRow::make('Nationality',[
				EasySelect::make('nationality',['items' => $nationalities, 'parent_class' => 'col-sm-6'])
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
			<hr/>
		
	</div>

	{{Form::close()}}

	{{HTML::script('js/lib/addressdropdown/addressdropdown.js')}}
	<script type="text/javascript">
	$(function(){
		@if(!isset($physician))
		AddressDropdown.config({server:'<?php echo URL::to('AddressDropdown') ?>'});
		@else
		AddressDropdown.config({
			server:'<?php echo URL::to('AddressDropdown') ?>',
			regcode:{{PrepareJSValue("regcode",$physician->regcode)}},
	        provcode:{{PrepareJSValue("provcode",$physician->provcode)}},
	        citycode:{{PrepareJSValue("citycode",$physician->citycode)}},
	        bgycode:{{PrepareJSValue("bgycode",$physician->bgycode)}}    
		});
		@endif
		AddressDropdown.implement();
	});
	</script>
	
	@yield('form_script')

@stop