@section('content')

	<div ng-app ng-controller="PatientListController" class="container-fluid">
		<div class="row">
			<div class="pull-left">
				@if(isset($inline))
					<a class="btn btn-success" href="#" ng-click="cancelPatientSelect()"><span class="glyphicon glyphicon-arrow-left"></span> Go Back</a>
				@endif
				{{HTML::link( (isset($inline) ? 'BloodRequest' : '') . 'Patient/create','Create New Patient',['class' => 'btn btn-primary'])}}
				{{HTML::link('#','Delete Patients',['class' => 'btn btn-danger delete-selection','form'=>'patients'])}}
			</div>
			{{Form::open()}}
			<div class="col-sm-3 pull-right input-group">
				<input type="text" class="form-control" placeholder="Search Patient Name" name="patient_name" value="{{Patient::getListFilter()}}" />
				<div class="input-group-btn">
					<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
					<a class="btn btn-default" href="{{URL::to((isset($inline) ? 'BloodRequest' : '').'Patient/clearfilter')}}"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</div>
			</div>
			{{Form::close()}}
			{{PageTitle((isset($inline) ? 'Click <a href="#" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-ok"></span></a> to Select Patient' : 'Patient Records'))}}
			
		</div>
		{{Form::open(['class' => 'row' , 'style' => 'margin-top:1em;' , 'id' => 'patients'])}}
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<tr>
						<th><input type="checkbox" class="checkall" /></th>
						<th><span class="has-tooltip" title="Hospital Record No.">HRN</span></th>
						<th>Name</th>
						<th>Name Suffix</th>
						<th>Gender</th>
						<th>Date of Birth</th>
						<th></th>
					</tr>
					@foreach($patients as $i => $patient)
					<tr>
						<td><input type="checkbox" class="index" name='index[]' value='{{$patient->patient_id}}' /></td>
						<td>{{$patient->patient_id}}</td>
						<td>{{ucwords($patient->fname.' '.$patient->mname.' '.$patient->lname)}}</td>
						<td>{{$patient->name_suffix}}</td>
						<td>{{getGender($patient->gender)}}</td>
						<td>{{getFormatedDate($patient->bdate)}}</td>
						<td>
							<a href="{{URL::to((isset($inline) ? 'BloodRequest' : '').'Patient/'.$patient->patient_id.'/edit')}}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
							<a href="#" class="btn btn-danger btn-xs delete-row" ><span class="glyphicon glyphicon-remove"></span></a>
							@if(isset($inline))
								<a href="#" class="btn btn-success btn-xs" ng:click="selectPatient('{{$patient->patient_id}}')" ><span class="glyphicon glyphicon-ok"></span></a>
							@endif
						</td>
					</tr>
					@endforeach
					@if(count($patients) == 0)
					<tr>
						<td colspan="7" align="center">No Patient Records</td>
					</tr>
					@endif
				</table>
			</div>
		{{Form::close()}}
		<div class="row">
			{{$patients->links()}}
		</div>
	</div>
	@yield('list_script')
@stop