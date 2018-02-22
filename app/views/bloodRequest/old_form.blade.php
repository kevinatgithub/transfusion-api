<?php
	$validation = isset($validation) ? $validation : null;

	/*Patient Details*/

	$r_patient_id = FormField::make(
					[
						TextField::make('patient_id','Patient ID',['class' => 'form-control','placeholder' => 'Patient ID']),
						ButtonField::make("<span class='glyphicon glyphicon-search'></span>",['class' => 'btn btn-success' , 'title' => 'Click to Select Patient'])
					],
					$validation,
					Form::label('patient_id','Patient ID',['class' => 'control-label col-sm-2']));
	$r_patient_name = FormField::make(
					[
						TextField::make('fname','First Name',['class' => 'form-control','placeholder' => 'First']),
						TextField::make('mname','Middle Name',['class' => 'form-control','placeholder' => 'Middle']),
						TextField::make('lname','Last Name',['class' => 'form-control', 'placeholder' => 'Last'])
					],$validation,
					Form::label('name','Patient Name',['class' => 'control-label col-sm-2']));

	$r_name_suffix = FormField::make(
					[
						TextField::make('name_suffix','Name Suffix',['class' => 'form-control','placeholder' => 'Name Suffix'])
					],$validation,
					Form::label('','Name Suffix',['class' => 'control-label col-sm-2']));

	$r_gender = FormField::make(
					[
						RadioButtonField::make('gender','Gender',['items' => ['M' => 'Male' , 'F' => 'Female'], 'container_class' => 'radio-inline'])
					],$validation,
					Form::label('','Gender',['class'=>'control-label col-sm-2']));

	/*Confinement Details*/
	$r_diagnosis = FormField::make(
					[
						TextAreaField::make('diagnosis','Diagnosis',['class' => 'form-control','parent-class' => 'col-sm-6','rows' => '4'])
					],$validation,
					Form::label('','Diagnosis',['class'=>'control-label col-sm-2']));

	$r_physician = FormField::make(
					[
						TextField::make('physician_name','Attending Physician',['class' => 'form-control','parent-class' =>'col-sm-4','placeholder' => 'Attending Physician']),
						ButtonField::make("<span class='glyphicon glyphicon-search'></span>",['class' => 'btn btn-success','title' => 'Click to Select Physician']),
						HiddenField::make('physician_id','Attending Physician',['class' => 'form-control'])
					],$validation,
					Form::label('','Attending Physician',['class' => 'control-label col-sm-2']));

	$r_hemo_level = FormField::make(
					[
						TextField::make('hemo_level','Latest Hemoglobin Level',['class' => 'form-control','placeholder' => 'Latest Hemoglobin Level'])
					],$validation,
					Form::label('','Latest Hemoglobin Level',['class' => 'control-label col-sm-2']));
?>

@section('content')
<div class="row">
	<h4 class="col-lg-3 col-md-5 col-sm-10">Create New Blood Request</h4>
	<div class="col-lg-1 col-md-1"></div>
	<div class="col-lg-8 col-md-6 col-sm-2">
		{{HTML::link('BloodRequest','Back to List',['class'=>'btn btn-primary'])}}		
	</div>
</div>
{{Form::open(['class' => 'form-horizontal','style' => 'margin-top:1em;'])}}
<fieldset>
	<legend><h4 style="margin-left:2em;">Patient Details</h4></legend>
	{{$r_patient_id->render()}}
	{{$r_patient_name->render()}}
	{{$r_name_suffix->render()}}
	{{$r_gender->render()}}
</fieldset>	
<fieldset style="margin-top:2em;">
	<legend><h4 style="margin-left:2em;">Patient Confinement Details</h4></legend>
	{{$r_diagnosis->render()}}
	{{$r_physician->render()}}
	{{$r_hemo_level->render()}}
</fieldset>
<fieldset style="margin-top:2em;">
	<legend><h4 style="margin-left:2em;">Blood Request Details</h4></legend>
	<div class="form-group">
		{{Form::label('','Blood Type',['class' => 'control-label col-sm-2'])}}
		<div class="col-sm-2">
			{{Form::select('blood_type',$blood_types,null,['class' => 'form-control'])}}
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4"><b class="pull-right">Blood Component</b></div>
		<div class="col-xs-2"><b>Quantity</b></div>
	</div>
	@foreach($components as $cc => $cn)
		<br/>
		<div class="row">
			<div class="col-sm-4">
				<p align="right">
					{{$cn}}
					<input type="checkbox" name="component[]" id="component_{{$cc}}" /></input>
				</p>
			</div>
			<div class="col-sm-2">
				{{Form::text('quantity[]',null,['class' => 'form-control' , 'placeholder' => $cn , 'disabled' => 'disabled'])}}
			</div>
		</div>
	@endforeach
</fieldset>
	<br/><br/>
	<div class="col-sm-2"></div>
	{{Form::submit('Create New Request',['class' => 'btn btn-primary'])}}
	{{Form::reset('Reset Form',['class' => 'btn btn-danger','style' => 'margin-left:1em;'])}}
	<br/><br/>
{{Form::close()}}

@stop