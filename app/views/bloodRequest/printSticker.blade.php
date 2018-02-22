<?php
	$detail->source = $detail->source;

?>
@section('content')
	<style>
		.ptable{
			margin:0 1em;
			width:260px;
			border-left:1px solid #a1a1a1;
			border-top:1px solid #a1a1a1;
			font-size:8px;
			text-transform: uppercase;

		}
		.ptable td{
			/*vertical-align: top;*/
			border-right: 1px solid #a1a1a1;
			border-bottom: 1px solid #a1a1a1;
			/*text-align: center;*/
		}
	</style>
	@if($type=='label')
		<table cellpadding="0" cellspacing="0" class="ptable">
			<tr>
				<td>Donation ID</td>
				<td>{{$detail->donation_id}}</td>
			</tr>
			<tr>
				<td>From</td>
				<td>
					@if($detail->source != '')
						{{$detail->source->facility_name}}
					@else
						Storage
					@endif
				</td>
			</tr>
			<tr>
				<td>Blood Type</td>
				<td>{{$bloodRequest->blood_type}}</td>
			</tr>
			<tr>
				<td>Component</td>
				<td>{{$detail->component->comp_name}}</td>
			</tr>
			<tr>
				<td>Expiration Date</td>
				<td>{{$detail->expiration_dt}}</td>
			</tr>
			<tr>
				<td>Volume</td>
				<td>{{$detail->component_vol}}</td>
			</tr>
		</table>
	@elseif($type=='crossmatch')
		<table cellpadding="0" cellspacing="0" class="ptable" >
			<tr>
				<td width="120">Donation ID</td><td>{{$detail->donation_id}}</td>
			</tr>
			<tr>
				<td>Patient HRN</td><td>{{$bloodRequest->patient->patient_id}}</td>
			</tr>
			<tr>
				<td>Patient</td><td>{{$bloodRequest->patient->getFullName()}}</td>
			</tr>
			<tr>
				<td>Date of Birth</td><td>{{date('d-M-Y',strtotime($bloodRequest->patient->bdate))}}</td>
			</tr>
			<tr>
				<td>Blood Type</td><td>{{$bloodRequest->blood_type}}</td>
			</tr>
			<tr>
				<td>Component</td><td>{{$detail->component->comp_name}}</td>				
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="ptable" style="margin-top:0px;border-top:none;">
			<tr>
				<td colspan="2" align="center">Cross-match: 
				@if($detail->crossmatch_result == 'C')
					Compatible
				@elseif($detail->crossmatch_result == 'T')
					Type Specific
				@elseif($detail->crossatch_result == 'I')
					Incompatible
				@endif
				</td>
			</tr>
			<tr>
				<td width="120" style="font-size:8px;">Date Performed</td>
				<td>
					{{date('M d, Y H:i:s',strtotime($detail->crossmatch_dt))}}
					
				</td>
			</tr>
		</table>
		<br/>
		<table cellpadding="0" cellspacing="0" class="ptable" >
			<tr>
				<td width="120">Donation ID</td><td>{{$detail->donation_id}}</td>
			</tr>
			<tr>
				<td>Patient HRN</td><td>{{$bloodRequest->patient->patient_id}}</td>
			</tr>
			<tr>
				<td>Patient</td><td>{{$bloodRequest->patient->getFullName()}}</td>
			</tr>
			<tr>
				<td>Date of Birth</td><td>{{date('d-M-Y',strtotime($bloodRequest->patient->bdate))}}</td>
			</tr>
			<tr>
				<td>Blood Type</td><td>{{$bloodRequest->blood_type}}</td>
			</tr>
			<tr>
				<td>Component</td><td>{{$detail->component->comp_name}}</td>				
			</tr>
		</table>
		<table cellpadding="0" cellspacing="0" class="ptable" style="margin-top:0px;border-top:none;">
			<tr>
				<td colspan="2" align="center">Cross-match: 
				@if($detail->crossmatch_result == 'C')
					Compatible
				@elseif($detail->crossmatch_result == 'T')
					Type Specific
				@elseif($detail->crossatch_result == 'I')
					Incompatible
				@endif
				</td>
			</tr>
			<tr>
				<td width="120" style="font-size:8px;">Date Performed</td>
				<td>
					{{date('M d, Y H:i:s',strtotime($detail->crossmatch_dt))}}
					
				</td>
			</tr>
		</table>
	@endif
		
@stop