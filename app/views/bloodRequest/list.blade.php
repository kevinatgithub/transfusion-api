<?php
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
	
?>
@section('content')
	
	<div class="row">
		<div class="col-md-2 col-sm-12 pull-left">
			<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Blood Requests</h4>	
		</div>
		<div class="col-md-10 col-sm-12">
			{{HTML::link('BloodRequest/create','Create Request',['class' => 'btn btn-primary btn-sm'])}}
			{{HTML::link('#','Delete Blood Requests',['class' => 'btn btn-sm btn-danger delete-selection','form'=>'bloodRequests'])}}
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12">&nbsp;</div>
	</div>
	{{Form::open(['id' => 'bloodRequests'])}}
		{{EasyHidden::make('search',[],null,true)->render()}}
		<div class="row">
			<div class="col-md-2 col-xs-12 pull-left">
				<div class="table-responsive">
					<table class="table table-sm table-bordered">
						<tr>
							<th>Search Request</th>
						</tr>
						<!--
						<tr>
							<td>
								{{EasyText::make('request_id',['placeholder' => 'Request ID'],null,Session::get('BloodRequest_request_id'))->render()}}
							</td>
						</tr>
						-->
						<tr>
							<td>
								{{EasyText::make('donation_id',['placeholder' => 'Donation ID','class' => 'has-tooltip','title' => 'Blood Unit Donation ID'],null,Session::get('BloodRequest_donation_id'))->render()}}
							</td>
						</tr>
						<tr>
							<td>
								{{EasyText::make('patient_id',['placeholder' => 'Patient HRN','class' => 'has-tooltip','title' => 'Patient Hospital Record No.'],null,Session::get('BloodRequest_patient_id'))->render()}}
							</td>
						</tr>
						<tr>
							<td>
								{{EasyText::make('patient_name',['placeholder' => 'Patient Name'],null,Session::get('BloodRequest_patient_name'))->render()}}
							</td>
						</tr>
						<tr>
							<td>
								{{EasyText::make('physician_name',['placeholder' => 'Attending Physician'],null,Session::get('BloodRequest_physician_name'))->render()}}
							</td>
						</tr>
						<tr>
							<td>
								{{Form::submit('Search',['class' => 'btn btn-success col-sm-12'])}}
								<br/><br/>
								{{HTML::link('BloodRequest/clearfilters','Show All',['class' => 'btn btn-warning col-sm-12'])}}
							</td>
						</tr>
					</table>
				</div>
			</div>
			<div class="col-md-10 col-sm-12 ">
				<div class="table-responsive">
					<table class="table table-bordered table-stripped">
						<tr>
							<th><input class="checkall" type="checkbox" /></th>
							<!--<th>Date Created</th>-->
							<th><span class="has-tooltip" title="Patitent Hospital Record No.">Patient HRN</span></th>
							<th>Patient Name</th>
							<th>Diagnosis</th>
							<th>Attending Physician</th>
							<th>Status</th>
							<th></th>
						</tr>
						@foreach($bloodRequests as $bloodRequest)
						<tr>
							<td>
								<input class="index" type="checkbox" name="index[]" value="{{$bloodRequest->request_id}}" />
							</td>
							<!--<td>{{getFormatedDate($bloodRequest->created_dt,false,true)}}</td>-->
							<td>{{$bloodRequest->patient_id}}</td>
							<td>
								@if($bloodRequest->Patient->disable_flg == 'N')
									{{$bloodRequest->Patient->getFullName()}}
								@else
									<span class="text-deleted">Record Deleted</span>
								@endif
							</td>
							<td>{{$bloodRequest->diagnosis}}</td>
							<td>
								@if($bloodRequest->Physician->disable_flg == 'N')
									{{$bloodRequest->Physician->getFullName()}}
								@else
									<span class="text-deleted">Record Deleted</span>
								@endif
							</td>
							<td>{{BloodRequest::getStatusValue($bloodRequest->status,false)}}</td>
							<td>
								<a class="btn btn-xs btn-info has-tooltip" title="Click to View Blood Request Details" href="{{URL::to('BloodRequest/'.$bloodRequest->request_id.'/view')}}"><span class="glyphicon glyphicon-th-list"></span></a>
								<a class="btn btn-xs btn-danger has-tooltip delete-row" title="Click to Delete Blood Request" href="#"><span class="glyphicon glyphicon-remove"></span></a>
							</td>
						</tr>
						@endforeach
						@if(count($bloodRequests) == 0)
						<tr>
							<td colspan="9" align="center">No Blood Request Yet</td>
						</tr>
						@endif
					</table>
				</div>
				{{$bloodRequests->links()}}
			</div>
		</div>
	{{Form::close()}}
	
@stop