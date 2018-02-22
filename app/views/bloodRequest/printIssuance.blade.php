<?php
	$config = FacilityConfig::current();
?>
@section('content')
<style>
#paper{
	width:1000px;
	font-size:12px;
	/*border:1px solid #000;*/
	padding:0;
	padding-left:20px;
	padding-right: 20px;
}
#paper h2,p{
	width:100%;
	text-align: center;
}
#paper table{
	width:90%;
	font-size: 12px;
}
#paper table th{
	text-align: left;
}
.unit td,.unit th{
	border:1px solid #000;
}
</style>

<div id="paper">
	<h2>{{$user->facility->facility_name}}</h2>
	<p>{{$user->facility->getAddress()}}</p>
	<p><b>Blood Transfusion Form</b></p>
	<hr style='border:1px solid #000;width:90%;' />
	<table style="margin-left:auto;margin-right:auto;">
		<tr>
			<th style="width:150px;">Hospital Record No.</th><td>: {{$bloodRequest->patient_id}}</td>
			<th>Blood Type</th><td><h4>: {{$bloodRequest->blood_type}}</h4></td>
			<th>Date</th><td>: {{date('M d, Y')}}</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<th>Patient Name</th><td>: {{$bloodRequest->patient->getFullName()}}</td>
			<th>Gender</th><td>: {{$bloodRequest->patient->getGender()}}</td>
			<th>Date of Birth</th><td>: {{$bloodRequest->patient->getBdate()}}</td>
		</tr>
		<tr>
			@if($config != null)
				@if($config->enable_patient_ward_no == 'Y')
					<th>Ward No.</th><td>: {{$bloodRequest->ward_no}}</td>
				@endif
				@if($config->enable_patient_room_no == 'Y')
					<th>Room No.</th><td>: {{$bloodRequest->room_no}}</td>
				@endif
				@if($config->enable_patient_bed_no == 'Y')
					<th>Bed No.</th><td>: {{$bloodRequest->bed_no}}</td>
				@endif
			@endif
		</tr>
		<tr>
			<th>Latest Hemoglobin Level</th><td colspan="5">: {{$bloodRequest->hemo_level}}</td>
		</tr>
		<tr>
			<td colspan="6">&nbsp;</td>
		</tr>
		<tr>
			<th>Diagnosis</th><td>: {{$bloodRequest->diagnosis}}</td>
			<th>Physician</th><td>: {{$bloodRequest->physician->getFullName()}}</td>
			<th>Verified By</th><td>: {{$bloodRequest->verifier->getFullName()}}</td>
		</tr>
		<tr>
			<td colspan="6"></td>
		</tr>
	</table>
	<br/><br/>
	<table style="margin-left:auto;margin-right:auto;text-align:center;width:90%;" id="units">
		<tr class="unit" style="height:50px;">
			<th style="text-align:center; width:130px;">Serial Number</th>
			<th style="text-align:center;">Blood<br/>Type</th>
			<th style="text-align:center;">Expiration<br/>Date</th>
			<th style="text-align:center; width:100px;">Component</th>
			<th style="text-align:center;">X-matched<br/>Result</th>
			<th style="text-align:center;">X-matched<br/>By</th>
			<th style="text-align:center;">Released<br/>By</th>
			<th style="text-align:center;">Taken<br/>By</th>
		</tr>
		@if(count($details) == 0)
		<tr class="unit">
			<td colspan="8" align="center">No Blood Units</td>
		</tr>
		@endif
		@foreach($details as $detail)
		<tr class="unit" style="height:35px;">
			<td>{{$detail->donation_id}}</td>
			<td>
				@if($detail->donation != null)
					{{$detail->donation->blood_type}}
				@else
					{{$bloodRequest->blood_type}}
				@endif
			</td>
			<td>{{date('Y-m-d',strtotime($detail->expiration_dt))}}</td>
			<td>{{$detail->component->comp_name}}</td>
			<td>
				@if($detail->crossmatch_result == 'T')
					@if($detail->component_cd == '30' || $detail->component_cd == '40' || $detail->component_cd == '50' || $detail->component_cd == '60')
						Type specific
					@else
						On-going
					@endif
				@else
					Compatible
				@endif
			</td>
			<td>
				
			</td>
			<td></td>
			<td></td>
		</tr>
		@endforeach
	</table>
	<br/><br/>
	<table align="center" height="200" style="margin-left:auto;margin-right:auto;width:600px;">
		<tr>
			<td align="center" width="300">________________________<br/>Medical Technologist</td>
			<td>
				<table>
					<tr>
						<td>Received by:________________________</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<tr>
						<td>Date & Time:________________________</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td align="center">________________________<br/>Head, Blood Services</td>
			<td>Transaction No: {{$bloodRequest->request_id}}</td>
		</tr>
	</table>
</div>
@stop