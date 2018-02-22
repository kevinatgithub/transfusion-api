@section('content')
	<div ng-app ng-controller='PhysicianListController' class="container-fluid">
		<div class="row">
			<div class="pull-left">
				@if(isset($inline))
					<a class="btn btn-success" href="#" ng-click="cancelPhysicianSelect()"><span class="glyphicon glyphicon-arrow-left"></span> Go Back</a>
				@endif
				{{HTML::link( (isset($inline) ? 'BloodRequest' : '') . 'Physician/create','Create New Physician',['class' => 'btn btn-primary'])}}
				{{HTML::link('#','Delete Physicians',['class' => 'btn btn-danger delete-selection','form'=>'physicians'])}}
			</div>
			{{Form::open()}}
			<div class="col-sm-3 pull-right input-group">
				<input type="text" class="form-control" placeholder="Search Physician Name" name="physician_name" value="{{Physician::getListFilter()}}" />
				<div class="input-group-btn">
					<button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
					<a class="btn btn-default" href="{{URL::to((isset($inline) ? 'BloodRequest' : '').'Physician/clearfilter')}}"><span class="glyphicon glyphicon-remove-sign"></span></a>
				</div>
			</div>
			{{Form::close()}}
			{{PageTitle((isset($inline) ? 'Click <a href="#" class="btn btn-success btn-sm" ><span class="glyphicon glyphicon-ok"></span></a> to Select Physician' : 'Physician Records'))}}
		</div>
		{{Form::open(['class' => 'row' , 'style' => 'margin-top:1em;' , 'id' => 'physicians'])}}
			<div class="table-responsive">
				<table class="table table-striped table-bordered">
					<tr>
						<th><input type="checkbox" class="checkall" /></th>
						<th>Physician ID</th>
						<th>Name</th>
						<th>Name Suffix</th>
						<th>Gender</th>
						<th>Date of Birth</th>
						<th></th>
					</tr>
					@foreach($physicians as $i => $physician)
					<tr>
						<td><input type="checkbox" class="index" name='index[]' value='{{$physician->physician_id}}' /></td>
						<td>{{$physician->physician_id}}</td>
						<td>{{ucwords($physician->fname.' '.$physician->mname.' '.$physician->lname)}}</td>
						<td>{{$physician->name_suffix}}</td>
						<td>{{getGender($physician->gender)}}</td>
						<td>{{getFormatedDate($physician->bdate)}}</td>
						<td>
							<a href="{{URL::to((isset($inline) ? 'BloodRequest' : '').'Physician/'.$physician->physician_id.'/edit')}}" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-edit"></span></a>
							<a href="#" class="btn btn-danger btn-xs delete-row" ><span class="glyphicon glyphicon-remove"></span></a>
							@if(isset($inline))
								<a href="#" class="btn btn-success btn-xs" ng:click="selectPhysician('{{$physician->physician_id}}')" ><span class="glyphicon glyphicon-ok"></span></a>
							@endif
						</td>
					</tr>
					@endforeach
					@if(count($physicians) == 0)
					<tr>
						<td colspan="7" align="center">No Physician Records</td>
					</tr>
					@endif
				</table>
			</div>
		{{Form::close()}}
		<div class="row">
			{{$physicians->links()}}
		</div>
	</div>
	@yield('list_script')
@stop