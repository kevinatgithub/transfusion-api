<?php
	foreach($units as $unit){
		$unit->donation;
	}
?>
@section('content')
	{{Form::open()}}
		<div class="row"><h3 class="col-xs-12 text-info">In-house Blood Units</h3></div>
	<div class="row">
	<div class="col-sm-3">
		<b>{{strtoupper($component->comp_name)}}</b> |
		<b>{{strtoupper($blood_type)}}</b> 
	</div>

		<div class="col-sm-4 pull-right">
			<div class="input-group">
				<input class="form-control" type="text" placeholder="Search Donation ID" name="donation_id" value="{{Session::get('BloodUnit_donation_id')}}" />
				<div class="input-group-btn">
					<button class="btn btn-default" onclick="$(this).parents('form:first').submit()"><span class="glyphicon glyphicon-search"></span></button>
					<a href="{{URL::to('BloodUnit/'.$detail_id.'/'.$str_ids.'/'.$component->component_cd.'/'.$blood_type.'/clearFilter')}}" class="btn btn-default"><span class="glyphicon glyphicon-remove"></span></a>
				</div>
			</div>
		</div>
		<a class="btn btn-danger pull-right" href="#" onclick="parent.cancelLookUp()">Cancel</a>
		@if(User::current()->facility_cd != '13109')
	    <a href="{{URL::to('BloodUnit/'.$detail_id.'/'.$str_ids.'/'.$component->component_cd.'/'.$blood_type.'/otherSource')}}" class="btn btn-info pull-right" style="margin-right:1em;"><span class="glyphicon glyphicon-export"></span> From Outsourced</a>    	
	    @endif
	</div>
	{{Form::close()}}
	<br/>
	<div class="table-responsive" ng:app ng:controller="BloodUnitSelect">
		<table class="table table-bordered table-stripped">
			<tr>
				<th>Donation ID</th>
				<th>Date Collected</th>
				<th>Expiration Date</th>
				<th></th>
			</tr>
			<?php $js_units = []; ?>
			@foreach($units as $i => $unit)
			<?php $js_units[$unit->donation_id] = $unit ?>
			<tr>
				<td>{{$unit->donation_id}}</td>
				<td>
					@if($unit->donation != null)
						{{date('M d, Y',strtotime($unit->donation->getDonationDate()))}}
					@else
						Originally Out Sourced
					@endif
				</td>
				<td>{{date('M d, Y H:i:s',strtotime($unit->expiration_dt))}}</td>
				<td>
					<a class="btn btn-xs btn-success" href="#" ng:click="selectUnit('{{$unit->donation_id}}')"><span class="glyphicon glyphicon-check"></span></a>
				</td>
			</tr>
			@endforeach
			@if(count($units) == 0)
			<tr>
				<td colspan="4" align="center">No Units Available</td>
			</tr>
			@endif
		</table>
	</div>
	{{$units->links()}}
	<script type="text/javascript">
	function BloodUnitSelect($scope){
		$scope.units = {{json_encode($js_units)}};
		$scope.selectUnit = function(donation_id){
			unit = $scope.units[donation_id];
			parent.selectUnit(unit);
		}
	}
	</script>
@stop