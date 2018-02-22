<?php
	$patient = $bloodRequest->patient;
	$physician = $bloodRequest->physician;
	$details = $bloodRequest->details;
	$js_details = array();
	foreach($details as $i => $d){
		$d['component'];
		$d['donation'];
		$d['bloodUnit'];
		$d['source'];
		//$d->mbd = MBD::where('sched_id','=',$d['donation']['sched_id'])->first();
		$d['donation']['mbd'];
		$js_details[$d->id] = $d;
	}

	$config = FacilityConfig::current();
	//echo "<pre>";	dd($details[0]);
	//$js_details = $details;

	EasyRow::$global_label_container = "<label class='control-label col-sm-3'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
?>
@section('content')
	<div ng:app="BloodRequestView" ng:controller="BloodRequestController" ng:cloak>
		
		<div class="row">
			<div class="col-md-2 col-sm-5 pull-left">
				<h4 class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Manage Blood Requests</h4>	
			</div>
			<div class="col-md-10 col-sm-7">
				<ul class="nav nav-tabs">
					<li><a href="{{URL::to('BloodRequest')}}" ><span class="glyphicon glyphicon-arrow-left"></span> Back to List</a></li>
					<li ng:class="view.form ? 'active' : ''"><a href="#" ng:click="changeView(0)"><span class="glyphicon glyphicon-th-list"></span> View Details</a></li>
					<li ng:class="lookup.form || lookup.frame ? 'active' : ''" ng:hide="bloodRequest.status == 'C'"><a href="#" ng:click="changeView(1)"><span class="glyphicon glyphicon-search"></span> Look Up Units</a></li>
					<li ng:class="crossmatch.form ? 'active' : ''"><a href="#" ng:click="changeView(2)" ng:hide="bloodRequest.status == 'C'"><span class="glyphicon glyphicon-tags"></span> Cross-match</a></li>
					<li ng:class="release.form ? 'active' : ''"><a href="#" ng:click="changeView(3)"><span class="glyphicon glyphicon-transfer"></span> Return / Release</a></li>
					<li ng:class="investigate.form ? 'active' : ''"><a href="#" ng:click="changeView(6)" ng:hide="bloodRequest.status == 'C'"><span class="glyphicon glyphicon-eye-open"></span> Investigate</a></li>
					<li ng:class="cancel.form ? 'active' : ''"><a href="#" ng:click="changeView(4)" ng:hide="bloodRequest.status == 'C' || bloodRequest.status == 'I'"><span class="glyphicon glyphicon-trash"></span> Cancel Request</a></li>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-sm-12">&nbsp;</div>
		</div>
		{{Form::open(['id' => 'bloodRequests'])}}
			{{EasyHidden::make('search',[],null,true)->render()}}
			<div class="row">
				
				<div class="col-lg-10 col-md-9 col-xs-12 pull-right">
					
					<!-- Blood Request Details -->
					<div class="table-responsive" ng:show="view.form">
						<table class="table table-bordered">
							<tr class="bg-gray">
								<td width="40"></td>
								<th colspan="8"><span class="glyphicon glyphicon-th-list"></span> Blood Request Details
								<span >
									<a class="btn btn-xs btn-warning pull-right" href="#" ng:click="view.toggleEditMode()" ng:hide="view.editMode"><span class="glyphicon glyphicon-edit"></span> Edit Details</a>
								</span>
								<a class="btn btn-xs btn-danger pull-right" href="#" ng:click="view.cancelChanges()" ng:show="view.editMode"><span class="glyphicon glyphicon-remove"></span> Cancel</a>
								<a class="btn btn-xs btn-success pull-right" href="#" ng:click="view.saveChanges()" ng:show="view.editMode" style="margin-right:0.5em;"><span class="glyphicon glyphicon-ok"></span> Save Changes</a>
								</th>
							</tr>
 							<tr>
 								<td></td>
 								<th>Component</th>
 								<th>Donation ID</th>
 								<th>Outsourced</th>
 								<th>Date Collected</th>
 								<th>Expiration Date</th>
 								<th>Volume</th>
 								<th>Cross-Match Result</th>
 								<th>Status</th>
 							</tr>
							<tr ng:repeat="detail in view.details" ng:hide="detail.forRemoval">
								<td>
									<span  ng:show="view.editMode">
										<a class="btn btn-xs btn-info has-tooltip" href="#" ng:hide="detail.expiration_dt == null" title="This Detail is locked because it has blood unit attached, you may return blood unit at the Release/Return tab to mark it as Available"><span class="glyphicon glyphicon-lock"></span></a>
										<a class="btn btn-xs btn-danger" href="#" ng:show="detail.expiration_dt == null" ng:click="view.removeDetail(detail)"><span class="glyphicon glyphicon-remove"></span></a>
									</span>
									@if(User::current()->facility_cd != '13109')
									<span ng:hide="view.editMode">
										<a class="btn btn-xs btn-warning has-tooltip" href="#" ng:click="view.printSticker(detail.id,'label')" ng:show="detail.expiration_dt" title="Click to print label"><span class="glyphicon glyphicon-print"></span></a>
									</span>
									@endif
								</td>
								<td><%detail.component.comp_name%></td>
								<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null && detail.source_id != '' ? detail.collected_dt : detail.getDonationDate()) : null%></td>
								<td><%detail.expiration_dt%></td>
								<td><%detail.component_vol%></td>
								<td>
									<span style="<% crossmatch.getStyle(detail,true) %>"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
								</td>
								<td><%detail.unit_stat | unitStat%></td>
							</tr>
							<tr ng:show="view.editMode">
								<td colspan="9">
								<a class="btn btn-primary pull-right" href="#" ng:click="view.addNewDetail()"><span class="glyphicon glyphicon-pluss"></span> Add New Detail</a>
									<div class="col-sm-3 pull-right">
										{{EasySelect::make('new_component_cd',['class' => 'form-control','items' => $item_components,'ng:model' => 'view.newDetail_component_cd'])->render()}}	
									</div>
								</td>
							</tr>
						</table>
					</div>

					<!-- Look Up Blood Units -->
					<div class="table-responsive" ng:show="lookup.form" ng:controller="LookUpController">
						<table class="table table-bordered">
							<tr>
								<td class="bg-gray"></td>
								<th colspan="7" class="bg-gray">
									<span class="glyphicon glyphicon-search"></span> Look Up Blood Units
									<span >
										<a class="btn btn-xs btn-warning pull-right" href="#" ng:click="lookup.toggleEditMode()" ng:hide="lookup.editMode">Search Blood Unit</a>
									</span>
									<a class="btn btn-xs btn-danger pull-right" href="#" ng:click="lookup.toggleEditMode()" ng:show="lookup.editMode"><span class="glyphicon glyphicon-remove"></span>  Cancel</a>
									<a class="btn btn-xs btn-success pull-right" href="#" ng:click="lookup.saveChanges()" ng:show="lookup.editMode" style="margin-right:1em;"><span class="glyphicon glyphicon-ok"></span>  Save Changes</a>
								</th>
							</tr>
 							<tr>
 								<th width="40"></th>
 								<th>Component</th>
 								<th>Donation ID</th>
 								<th>Outsourced</th>
 								<th>Date Collected</th>
 								<th>Expiration Date</th>
 								<th>Volume</th>
 								<th>Cross-Match Result</th>
 							</tr>
							<tr ng:repeat="detail in lookup.details">
								<td><a href="#" ng:show="lookup.editMode ? !detail.expiration_dt : false" class="btn btn-success btn-xs" ng:click="lookup.openBloodUnitFrame(detail)" ><span class="glyphicon glyphicon-search"></span></a></td>
								<td><%detail.component.comp_name%></td>
								<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? detail.collected_dt : detail.getDonationDate()) : null%></td>
								<td><%detail.expiration_dt%></td>
								<td><%detail.component_vol%></td>
								<td>
									<span style="<% crossmatch.getStyle(detail,true) %>"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
								</td>
							</tr>
						</table>
					</div>

					<!-- Iframe : Blood Unit Select -->
					<iframe id="lookup_frame" ng:show="lookup.frame" src="<% lookup.frame_src %>" style="width:100%;height:1400px;border:none;overflow:none;"></iframe>

					<!-- Cross-Matching -->
					<div class="table-responsive" ng:show="crossmatch.form" ng:controller="CrossmatchController">
						<table class="table table-bordered">
							<tr>
								<td class="bg-gray"></td>
								<th colspan="7" class="bg-gray"><span class="glyphicon glyphicon-tags"></span> Cross-Matching
									<span >
										<a class="btn btn-warning btn-xs pull-right" href="#" ng:click="crossmatch.toggleEditMode()" ng:hide="crossmatch.editMode">Submit Cross-match Result</a>
									</span>
									<a class="btn btn-danger btn-xs pull-right" href="#" ng:click="crossmatch.toggleEditMode()" ng:show="crossmatch.editMode"><span class="glyphicon glyphicon-remove"></span>  Cancel</a>
									<a class="btn btn-success btn-xs pull-right right-offset" href="#" ng:click="crossmatch.saveChanges()" ng:show="crossmatch.editMode"><span class="glyphicon glyphicon-ok"></span> Save Changes</a>
								</th>
							</tr>
 							<tr>
 								<td></td>
 								<th>Component</th>
 								<th>Donation ID</th>
 								<th>Outsourced</th>
 								<th>Date Collected</th>
 								<th>Expiration Date</th>
 								<th>Cross-Match Result</th>
								<th>Cross-match Date</th>
 							</tr>
							<tr ng:repeat="detail in crossmatch.details">
								<td>
									<span ng:hide="crossmatch.editMode">	
										<a class="btn btn-xs btn-warning has-tooltip" ng:show="detail.crossmatch_result" ng:click="crossmatch.printSticker(detail.id,'crossmatch')" title="Click to Print Crossmatch Result Label"><span class="glyphicon glyphicon-print"></span></a>
									</span>
									<span ng:show="{{$config['facility_cd'] == '13109' ? 1 : 0}}">
										<span ng:hide="crossmatch.editMode">	
											<a class="btn btn-xs btn-info has-tooltip" ng:show="detail.crossmatch_result" ng:click="crossmatch.previewCompatibilityTestResult(detail.id,'crossmatch')" title="Click to Print Compatibility Test Result"><span class="glyphicon glyphicon-print"></span></a>
										</span>
									</span>
								</td>
								<td><%detail.component.comp_name%></td>
								<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null && detail.source_id != '' ? detail.collected_dt : detail.getDonationDate()) : null%></td>
								<td><%detail.expiration_dt%></td>
								<td>
									<span style="<% crossmatch.getStyle(detail,true) %>" ng:hide="crossmatch.editable(detail)"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : null)%></span>
									<span ng:show="crossmatch.editable(detail)">
										<select class="form-control" ng:model="detail.crossmatch_result" style="<% crossmatch.getStyle(detail) %>" ng:hide="crossmatch.crossmatchState(detail)">
											<option value="">Please Select</option>
											<option value="C" class="text-success">Compatible</option>
											<option value="I" class="text-danger">Incompatible</option>
											<option value="T" class="text-info" >Type specific</option>
										</select>
									</span>
									<b class="text-info" ng:show="crossmatch.crossmatchState(detail)">Type Specific</b>
								</td>
								<td><%detail.crossmatch_dt%></td>
							</tr>
						</table>
					</div>

					<!-- Return / Release -->
					<div class="table-responsive" ng:show="release.form" ng:controller="ReleaseController">
						<table class="table table-bordered" ng:hide="release.issuanceInitiated">
							<tr class="bg-gray">
								<td width="40"></td>
								<th colspan="8"><span class="glyphicon glyphicon-transfer"></span> Blood Unit Release / Return
								<a class="btn btn-warning btn-xs pull-right" href="#" ng:click="release.reserveRequest(false)" ><span class="glyphicon glyphicon-arrow-right"></span> Reserve Blood Request</a>
								<a class="btn btn-primary btn-xs pull-right" href="#" ng:click="release.openIssuanceForm()" ng:show="bloodRequest.status == 'R'"><span class="glyphicon glyphicon-arrow-right"></span> Release Blood Units</a>
								<!--<a class="btn btn-info btn-xs pull-right" href="#" ng:show="bloodRequest.status == 'I'" ng:click="release.openIssuanceReciept()"><span class="glyphicon glyphicon-print"></span> Print Issuance Form</a>-->
								
								</th>
							</tr>
 							<tr>
 								<td></td>
 								<th>Component</th>
 								<th>Donation ID</th>
 								<th>Outsourced</th>
 								<th>Date Collected</th>
 								<th>Expiration Date</th>
 								<th>Volume</th>
 								<th>Cross-Match Result</th>
 								<th>Status</th>
 							</tr>
							<tr ng:repeat="detail in release.details" >
								<td>
									<span ng:hide="detail.unit_stat == 'X' || detail.unit_stat == 'D'">
										<span ng:show="detail.expiration_dt != null">
											<a class="btn btn-warning btn-xs has-tooltip" href="#" ng:hide="detail.processing" data-toggle="modal" data-target="#cancelDetailForm" title="Click to Return / Discard Blood Unit" ng:click="release.setCurrent(detail)"><span class="glyphicon glyphicon-arrow-left"></span></a>
											<img src="{{URL::to('/loading.gif')}}" class="img-responsive" ng:show="detail.processing" />
										</span>
									</span>
								</td>
								<td><%detail.component.comp_name%></td>
								<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null && detail.source_id != '' ? detail.collected_dt : detail.getDonationDate()) : null%></td>
								<td><%detail.expiration_dt%></td>
								<td><%detail.component_vol%></td>
								<td>
									<span style="<% crossmatch.getStyle(detail,true) %>"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
								</td>
								<td><%detail.unit_stat | unitStat%></td>
							</tr>
						</table>


						<!-- Modal : Return / Discard Blood Unit -->
						<div class="modal fade" id="cancelDetailForm" tabindex="-1" role="dialog" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						        <h4 class="modal-title text-info" id="myModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Return / Discard Blood Unit</h4>
						      </div>
						      <div class="modal-body">
						      		<table class="table">
						      			<tr>
						      				<th width="150">Reference ID</th><td><% release.current.id %></td>
						      			</tr>
						        		<tr>
						        			<th width="150"><% release.current.donation_id == null ? 'Source' : 'Donation ID' %></th><td><% release.current.donation_id == null ? release.current.source.facility_name : release.current.donation_id %></td>
						        		</tr>
						        		<tr>
						        			<th width="150">Component</th><td><% release.current.component.comp_name %></td>
						        		</tr>
						        		<tr>
						        			<th width="150">Volume</th><td><% release.current.component_vol %></td>
						        		</tr>
						        		<tr>
						        			<th width="150">Expiration Date</th><td><% release.current.expiration_dt %></td>
						        		</tr>
						        		<tr>
						        			<th width="150">Action</th>
						        			<td>
						        				{{EasySelect::make('action',['items' => ['R' => 'Return', 'D' => 'Discard'],'ng:model' => 'release.action','class' => 'form-control'],null,'R')->render()}}	
						        			</td>
						        		</tr>
						        		<tr ng:show="release.action == 'R'">
						        			<th></th>
						        			<td>
						        				<textarea class="form-control" rows="8" cols="50" placeholder="Reason for Return" style="resize:none;" ng:model="release.current.return_reason"></textarea>
						        			</td>
						        		</tr>
						        		<tr ng:show="release.action == 'D'">
						        			<th>Reason for Discard</th>
						        			<td>
						        				{{EasySelect::make('discard_reason',['items' => $items_discard_reasons,'class' => 'form-control','ng:model' => 'release.other_reason_discard'])->render()}}
						        				<br/>
						        				<textarea ng:show="release.other_reason_discard == 'OTH'" class="form-control" rows="5" placeholder="Reason for Discard" ng:model="release.other_reason_discard_text"></textarea>
						        			</td>
						        		</tr>
						        	</table>

						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary" ng:click="release.returnUnit()">Done</button>
						      </div>
						    </div>
						  </div>
						</div>

						<!-- Modal : Reserve Blood Request -->
						<div class="modal fade" id="reserveRequestDetails" tabindex="-1" role="dialog" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						        <h4 class="modal-title text-info" id="myModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Reserve Blood Request</h4>
						      </div>
						      <div class="modal-body">
						      		<div class="table-responsive" ng:hide="release.reserving">
							      		<table class="table table-stripped table-bordered">
							      			<tr>
							      				<th class="bg-gray text-success" colspan="4"><span class="glyphicon glyphicon-check"></span> Compatible Blood Units</th>
							      			</tr>
							        		<tr>
							        			<th width="150">Donation ID</th><th>Component</th><th>Cross-Match Result</th><th>Status</th>
							        		</tr>
							        		<tr ng:repeat="detail in release.details" ng:show="(detail.crossmatch_result == 'C' || detail.crossmatch_result == 'T') && detail.unit_stat == 'R'">
							        			<td><% detail.donation_id == null ? 'From Other Souce' : detail.donation_id %></td>
							        			<td nowrap="nowrap"><% detail.component.comp_name %></td>
							        			<td>
							        				<span style="<% crossmatch.getStyle(detail,true) %>" ng:hide="crossmatch.editable(detail)"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
							        			</td>
							        			<td><% detail.unit_stat | unitStat %></td>
							        		</tr>
							        	</table>
						      		</div>
						      		<div class="col-sm-12" ng:show="release.reserving" align="center">
						      			<img src="{{URL::to('loading.gif')}}" width="20" /> 	Please Wait..
						      		</div>
						      		<br/>
						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary" ng:click="release.reserveRequest(true)">Proceed</button>
						      </div>
						    </div>
						  </div>
						</div>

						<!-- Release Form -->
						<div class="table-responsive col-md-10" ng:show="release.issuanceInitiated">
							<table class="table table-bordered">
								<tr class="bg-gray">
									<!-- <th>Source</th> --><th>Component</th><th>Donation ID</th><th></th>
								</tr>
								<tr ng:repeat="detail in release.forIssuance">
									<!-- <td width="200">
										<select class="form-control" ng:model="detail.source_type" ng:change="release.verifyUnitForIssuance(detail)">
											<option value="1">Internal</option>
											<option value="2">Other Source</option>
										</select>
									</td> -->
									<td width="200">
										<select class="form-control" ng:model="detail.component_cd" ng:change="release.verifyUnitForIssuance(detail)">
											@foreach($item_components as $cc => $cn)
												@if($cn != '')
													<option value="{{$cc}}">{{$cn}}</option>
												@endif
											@endforeach
										</select>
									</td>
									<td width="200">
										<input class="form-control" ng:model="detail.donation_id" type="text" placeholder="Donation ID" ng:change="release.verifyUnitForIssuance(detail)" />
										<!--<select class="form-control" ng:model="detail.source_id" ng:show="detail.source_type == 2" ng:change="release.verifyUnitForIssuance(detail)">
											@foreach($item_sources as $source_id => $facility_name)
											<option value="{{$source_id}}">{{$facility_name}}</option>
											@endforeach
										</select>-->
									</td>
									<td width="40">
										<img class="img" src="{{URL::to('loading.gif')}}" width="20" ng:show="detail.status == 'loading'" />
										<span class="glyphicon glyphicon-ok row-valign-middle text-success" ng:show="detail.status == true"></span>
										<span class="glyphicon glyphicon-remove row-valign-middle text-danger has-tooltip" ng:show="detail.status == false" title="<% detail.error %>"></span>
									</td>
								</tr>
								<tr class="bg-gray">
									<td colspan="4">
										<a class="btn btn-danger pull-right" href="#" ng:click="release.closeIssuanceForm()"><span class="glyphicon glyphicon-remove"></span> Cancel</a>
										<a class="btn btn-success pull-right right-offset" href="#" ng:click="release.confirmIssuance()">Release Blood Units</a>
										<i class="pull-right row-valign-middle text-danger right-offset">By submitting this form, you are verifying the Blood Type and Cross-match results</i>
									</td>
								</tr>
							</table>
						</div>
					</div>

					<!-- Investigate -->
					<div class="table-responsive" ng:show="investigate.form" ng:controller="InvestigateController">
						<div ng:hide="investigate.loading">
							<table class="table table-bordered" ng:hide="investigate.editMode">
								<tr class="bg-gray">
									<th colspan="9"><span class="glyphicon glyphicon-eye-open"></span> Investigate
									</th>
								</tr>
	 							<tr>
	 								<th width="40"></th>
	 								<th>Component</th>
	 								<th>Donation ID</th>
	 								<th>Outsourced</th>
	 								<th>Date Collected</th>
	 								<th>Expiration Date</th>
	 								<th>Volume</th>
	 								<th>Cross-Match Result</th>
	 								<th>Status</th>
	 							</tr>
								<tr ng:repeat="detail in investigate.details" ng:hide="detail.unit_stat != 'I'" class="<% detail.reaction.length > 0 ? 'bg-danger' : null %>">
									<td><a class="btn btn-xs btn-warning" href="#" ng:click="investigate.showReactionForm(detail)"><span class="glyphicon glyphicon-eye-open"></span></a></td>
									<td><%detail.component.comp_name%></td>
									<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
									<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
									<td><%detail.expiration_dt != null ? (detail.source_id != null && detail.source_id != '' ? detail.collected_dt : detail.getDonationDate()) : null%></td>
									<td><%detail.expiration_dt%></td>
									<td><%detail.component_vol%></td>
									<td>
										<span style="<% crossmatch.getStyle(detail,true) %>"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
									</td>
									<td><%detail.unit_stat | unitStat%></td>
								</tr>
								<tr ng:show="bloodRequest.status != 'I'">
									<td colspan="9" align="center">No Blood Units Available</td>
								</tr>
							</table>

							<div class="row" ng:show="investigate.editMode">
								<div class="col-lg-4 col-xs-12 pull-right table-responsive">
									<table class="table table-bordered table-xs">
										<tr class="bg-gray">
											<th colspan="2">Blood Unit Details
											</th>
										</tr>
										<tr>
											<td width="180">Component</td><td><%investigate.current.component.comp_name%></td>
										</tr>
										<tr>
											<td>Donation ID</td><td><%investigate.current.expiration_dt != null ? (investigate.current.source_id != null ? 'N/A' : investigate.current.donation_id) : null%></td>
										</tr>
										<tr>
											<td>Outsourced</td><td><%investigate.current.expiration_dt != null ? (investigate.current.source_id != null ? 'YES' : 'NO') : null%></td>
										</tr>
										<tr>
											<td>Date Collected</td><td><%investigate.current.expiration_dt != null ? (investigate.current.source_id != null && investigate.current.source_id != '' ? 'N/A' : investigate.current.getDonationDate()) : null%></td>
										</tr>
										<tr>
											<td>Expiration Date</td><td><%investigate.current.expiration_dt%></td>
										</tr>
										<tr>
											<td>Volume</td><td><%investigate.current.component_vol%></td>
										</tr>
										<tr>
											<td>Cross-match Result</td><td>
												<span style="<% crossmatch.getStyle(investigate.current,true) %>"><% investigate.current.crossmatch_result == 'C' ? 'Compatible' : (investigate.current.crossmatch_result == 'I' ? 'Incompatible' : null)%></span>
											</td>
										</tr>
										<tr>
											<td>Status</td><td><%investigate.current.unit_stat | unitStat%></td>
										</tr>
									</table>
								</div>
								<style type="text/css">
									#investigate th.lbl{
										text-align: right;
										padding-top: 1em;
										padding-right: 1em;
									}
								</style>
								<div class="col-lg-8 col-xs-12 pull-left table-responsive">
									<table class="table table-bordered" id="investigate">
										<tr>
											<th colspan="4" class="text-info bg-gray">Transfusion
											<a class="btn btn-danger btn-xs pull-right" href="#" ng:click="investigate.cancelChanges()">Cancel</a>
											<a class="btn btn-success btn-xs pull-right right-offset" href="#" ng:click="investigate.saveReactionSelection()">Save Changes</a>
											</th>
										</tr>
										<tr>
											<th width="200" class="lbl">Started By</th>
											<td>
												{{EasyText::make('tfsn_start_by',['class' => 'form-control','placeholder' => 'Started By','ng:model' => 'investigate.current.tfsn_start_by'])->render()}}
											</td>
											<th class="lbl" width="100">Date/Time</th>
											<td width="180">
												{{EasyDateTime::make('tfsn_start_dt',['class' => 'form-control','placeholder' => 'Date/Time','ng:model' => 'investigate.current.tfsn_start_dt'])->render()}}
											</td>
										</tr>
										<tr>
											<th class="lbl">
												<span ng:hide="investigate.current.tfsn_stat == '2'">Completed By</span>
												<span ng:hide="investigate.current.tfsn_stat != '2'">Stopped By</span>
											</th>
											<td>
												{{EasyText::make('tfsn_end_by',['class' => 'form-control','placeholder' => 'Completed/Stopped By','ng:model' => 'investigate.current.tfsn_end_by'])->render()}}
											</td>
											<th class="lbl">Date/Time</th>
											<td>
												{{EasyDateTime::make('tfsn_end_dt',['class' => 'form-control','placeholder' =>'Date/Time','ng:model' => 'investigate.current.tfsn_end_dt'])->render()}}
											</td>
										</tr>
										<tr>
											<th class="lbl">Set Removed By</th>
											<td>
												{{EasyText::make('tfsn_set_remove_by',['class' => 'form-control','placeholder' => 'Set Removed By','ng:model' => 'investigate.current.tfsn_set_remove_by'])->render()}}
											</td>
											<th class="lbl">Date/Time</th>
											<td>
												{{EasyDateTime::make('tfsn_set_remove_dt',['class' => 'form-control','placeholder' =>'Date/Time','ng:model' => 'investigate.current.tfsn_set_remove_dt'])->render()}}
											</td>
										</tr>
										<tr>
											<th class="lbl">Remarks</th>
											<td colspan="3">
												<div class="radio">
													<label><input class="radio" type="radio" name="tfsn_stat" ng:model="investigate.current.tfsn_stat" value="1"></input> Transfusion completed without immediate transfusion reactions noted</label>
												</div>
												<div class="radio">
													<label><input class="radio" type="radio"  name="tfsn_stat" ng:model="investigate.current.tfsn_stat" value="2"></input> Transfusion stopped with transfusion reactions noted</label>
												</div>
												<div class="radio">
													<label><input class="radio" type="radio"  name="tfsn_stat" ng:model="investigate.current.tfsn_stat" value="3"></input> For transfusion reaction studies</label>
												</div>
											</td>
										</tr>
										<tr ng:show='investigate.current.tfsn_stat == "2" || investigate.current.tfsn_stat == "3"'>
											<th colspan="4" class="text-info bg-gray">Reaction</th>
										</tr>
										<tr ng:show='investigate.current.tfsn_stat == "2" || investigate.current.tfsn_stat == "3"'>
											<th class="lbl">Reactions</th>
											<td colspan="3">

												<div class="checkbox">
													@foreach(TransfusionReaction::getList() as $reaction_id => $type)
														<label><input type="checkbox" name="reaction_id" value="{{$reaction_id}}" ng:model="investigate.current.reactions['{{$reaction_id}}']"> {{$type}}</label>
													@endforeach
												</div>
											</td>
										</tr>
										<tr ng:show='investigate.current.tfsn_stat == "2" || investigate.current.tfsn_stat == "3"'>
											<th class="lbl">Vital Signs</th>
											<td colspan="3">
												<div class="row">
													
													<label class="control-label col-xs-1" style="margin-top:0.5em;">BP</label>
													<div class="col-xs-5">
														{{EasyText::make('tfsn_end_bp',['class' =>'form-control','placeholder' => 'BP','ng:model' => 'investigate.current.tfsn_end_bp'])->render()}}
													</div>
													<label class="control-label col-xs-1" style="margin-top:0.5em;">PR</label>
													<div class="col-xs-5">
														{{EasyText::make('tfsn_end_pr',['class' =>'form-control','placeholder' => 'PR','ng:model' => 'investigate.current.tfsn_end_pr'])->render()}}
													</div>
													
												</div>
												<div class="row" style="margin-top:1em;">
													
													<label class="control-label col-xs-1" style="margin-top:0.5em;">RR</label>
													<div class="col-xs-5">
														{{EasyText::make('tfsn_end_rr',['class' =>'form-control','placeholder' => 'RR','ng:model' => 'investigate.current.tfsn_end_rr'])->render()}}
													</div>
													<label class="control-label col-xs-1" style="margin-top:0.5em;">Temp</label>
													<div class="col-xs-5">
														{{EasyText::make('tfsn_end_temp',['class' =>'form-control','placeholder' => 'Temp','ng:model' => 'investigate.current.tfsn_end_temp'])->render()}}
													</div>
													
												</div>
											</td>
										</tr>
									</table>
								</div>
								
								<div class="col-lg-8 col-xs-12 pull-left table-responsive">

									<!-- <table class="table table-hover table-bordered">
									    <tr class="bg-gray">
									    	<th colspan="2">Reaction Type
									    	<a class="btn btn-danger btn-xs pull-right" href="#" ng:click="investigate.cancelChanges()"><span class="glyphicon glyphicon-remove"></span>  Cancel</a>
									    	<a class="btn btn-success btn-xs pull-right right-offset" href="#" ng:click="investigate.saveReactionSelection()">Save Selections</a>
									    	</th>
									    </tr>
									   	@foreach($transfusion_reactions as $reaction)
									   	<tr>
									   		<td><b class="text-danger">{{$reaction->type}}</b>
									   		<p>{{ucfirst($reaction->symptoms)}}</p>
									   		</td>
									   		<td width="40">
									   			<input class="form-control reaction" ng:model="investigate.current.reactions[{{$reaction->reaction_id}}]" type="checkbox" value="{{$reaction->reaction_id}}"  title="{{ucfirst($reaction->type)}}" ng:checked="investigate.current.reactions['{{$reaction->reaction_id}}']" />
									   		</td>
									   	</tr>
									   	@endforeach
									</table> -->
								</div>
							</div>
						</div>
						<div ng:show="investigate.loading">
							<img src="{{URL::to('loading.gif')}}" width="20" /> Please Wait..
						</div>
					</div>

					<!-- Cancel Blood Request -->
					<div class="table-responsive" ng:show="cancel.form" ng:controller="CancelController">
						<table class="table table-bordered" ng:hide="cancel.processing">
							<tr class="bg-gray">
								<th colspan="8"><span class="glyphicon glyphicon-trash"></span> Cancel Blood Request
								</th>
							</tr>
							<tr>
								<td colspan="8">
									<p class="text-danger" align="center">
									<span class="glyphicon glyphicon-warning-sign text-danger btn btn-lg"></span>
									Do you want to cancel current request?
									<a class="btn btn-success btn-sm" href="#" style="margin-left:2em;" ng:click="cancel.showForm()">Yes</a>
									<a class="btn btn-default btn-sm" href="#" ng:click="changeView(0)">No</a>
									</p>
								</td>
							</tr>
 							<tr class="bg-gray">
 								<th>Component</th>
 								<th>Donation ID</th>
 								<th>Outsourced</th>
 								<th>Date Collected</th>
 								<th>Expiration Date</th>
 								<th>Volume</th>
 								<th>Cross-Match Result</th>
 								<th>Status</th>
 							</tr>
							<tr ng:repeat="detail in cancel.details">
								<td><%detail.component.comp_name%></td>
								<td><%detail.expiration_dt != null ? detail.donation_id : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null ? 'YES' : 'NO') : null%></td>
								<td><%detail.expiration_dt != null ? (detail.source_id != null && detail.source_id != '' ? detail.collected_dt : detail.getDonationDate()) : null%></td>
								<td><%detail.expiration_dt%></td>
								<td><%detail.component_vol%></td>
								<td>
									<span style="<% crossmatch.getStyle(detail,true) %>"><% detail.crossmatch_result == 'C' ? 'Compatible' : (detail.crossmatch_result == 'I' ? 'Incompatible' : (detail.crossmatch_result == 'T' ? 'Type specific' : null))%></span>
								</td>
								<td><%detail.unit_stat | unitStat%></td>
							</tr>
						</table>

						<div class="container" ng:show="cancel.processing">
							<span class="glyphicon glyphicon-trash"></span> Please wait..
						</div>

						<!-- Cancel Form -->
						<div class="modal fade" id="cancelRequestForm" tabindex="-1" role="dialog" aria-hidden="true">
						  <div class="modal-dialog">
						    <div class="modal-content">
						      <div class="modal-header">
						        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
						        <h4 class="modal-title text-info" id="myModalLabel"><span class="glyphicon glyphicon-info-sign"></span> Cancel Blood Request</h4>
						      </div>
						      <div class="modal-body">
						      		<table class="table">
						        		<tr>
						        			<th width="150">Reason For Cancellation</th>
						        			<td>{{EasyTextArea::make('new_reason_name',['class' => 'form-control','ng:model'=>'cancel.reason','placeholder'=>'Reason for Cancellation'])->render()}}</td>
						        		</tr>
						        	</table>

						      </div>
						      <div class="modal-footer">
						        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
						        <button type="button" class="btn btn-primary" ng:click="cancel.perform()">Done</button>
						      </div>
						    </div>
						  </div>
						</div>
					</div>

					<!-- Verifier Form -->
					<div class="modal fade" id="verifierForm" tabindex="-1" role="dialog" aria-hidden="true">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					        <h4 class="modal-title text-danger" id="myModalLabel"><span class="glyphicon glyphicon-lock"></span> Confirmatory Verification Check</h4>
					      </div>
					      <div class="modal-body form-horizontal">
					      	<div class="form-group">
					      		<div class="col-sm-3"></div>
					      		<div class="col-sm-9 text-info"><span class="glyphicon glyphicon-info-sign"></span> &nbsp; Please call a Verifier to enter his/her Access Details</div>
					      	</div>
				      		{{EasyRow::make('Verifier User ID',[
				      			EasyPassword::make('v_user_id',['class' => 'form-control','parent_class' => 'col-sm-9', 'placeholder' => 'Verifier User ID','ng:model' => 'verifier.user_id'])
				      		],['class' => 'form-group'])->render()}}
				      		{{EasyRow::make('Password',[
				      			EasyPassword::make('v_password',['class' => 'form-control','parent_class' => 'col-sm-9', 'placeholder' => 'Password','ng:model' => 'verifier.password'])
				      		],['class' => 'form-group'])->render()}}
				      		
					      </div>
					      <div class="modal-footer">
					        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					        <a href="#" class="btn btn-primary" ng:click="verifier.verify()">Proceed</a>
					      </div>
					    </div>
					  </div>
					</div>
				</div>

				<!-- Blood Request Details -->
				<div class="col-lg-2 col-md-3 col-xs-12 pull-left">
					<div class="table-responsive">
						<table class="table table-sm table-bordered">
							<tr>
								<th colspan="2"  class="bg-gray">Request Details</th>
							</tr>
							<tr>
								<td width="100">Request ID</td><td>{{$bloodRequest->request_id}}</td>
							</tr>
							<tr>
								<td>Blood Type</td><td>{{$bloodRequest->blood_type}}</td>
							</tr>
							<tr>
								<td>Status</td><td>{{BloodRequest::getStatusValue($bloodRequest->status,false)}}</td>
							</tr>
							@if($bloodRequest->verifier != null)
							<tr ng:show="bloodRequest.verifier != null">
								<td>Verified By</td><td>{{$bloodRequest->verifier->getFullName()}}</td>
							</tr>
							@endif
							<tr>
								<th colspan="2"  class="bg-gray">Patient Details</th>
							</tr>
							@if($bloodRequest->patient->disable_flg == 'N')
								<tr>
									<td><span class="has-tooltip" title="Hospital Record No.">HRN</span></td><td>{{$bloodRequest->patient_id}}</td>
								</tr>
								<tr>
									<td>Patient Name</td><td>{{$patient->getFullName()}}</td>
								</tr>
								<tr>
									<td>Name Suffix</td><td>{{$patient->name_suffix}}</td>
								</tr>
								<tr>
									<td>Date of Birth</td><td>{{$patient->getBdate()}}</td>
								</tr>
								<tr>
									<td>Gender</td><td>{{$patient->getGender()}}</td>
								</tr>
								<tr>
									<td>Patient Care</td><td>
									@if($bloodRequest->patient_care == 'O')
										Outpatient
									@elseif($bloodRequest->patient_care == 'I')
										Inpatient
									@endif
									</td>
								</tr>
							@else
								<tr>
									<td colspan="2">
										<span class="text-deleted">Record Deleted</span>
									</td>
								</tr>
							@endif
							@if($config != null)
								@if($config->enable_patient_ward_no == 'Y')
								<tr>
									<td>Ward No.</td><td>{{$bloodRequest->ward_no}}</td>
								</tr>
								@endif
								@if($config->enable_patient_room_no == 'Y')
								<tr>
									<td>Room No.</td><td>{{$bloodRequest->room_no}}</td>
								</tr>
								@endif
								@if($config->enable_patient_bed_no == 'Y')
								<tr>
									<td>Bed No.</td><td>{{$bloodRequest->bed_no}}</td>
								</tr>
								@endif
							@endif
							<tr>
								<th colspan="2"  class="bg-gray">Diagnosis</th>
							</tr>
							<tr>
								<td colspan="2">{{$bloodRequest->diagnosis}}</td>
							</tr>
							<tr>
								<th colspan="2"  class="bg-gray">Attending Physician</th>
							</tr>
							@if($physician->disable_flg == 'N')
								<tr>
									<td colspan="2">{{$physician->getFullName()}}</td>
								</tr>
							@else
								<tr>
									<td colspan="2">
										<span class="text-deleted">Record Deleted</span>
									</td>
								</tr>
							@endif
							<tr>
								<th colspan="2"  class="bg-gray">Latest Hemoglobin Level</th>
							</tr>
							<tr>
								<td colspan="2">{{$bloodRequest->hemo_level}}</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		{{Form::close()}}
	
	</div>
	<script type="text/javascript">

		angular.module('BloodRequestFilters',[]).filter('unitStat',function(){
			return function(input){
				if(input == null){
					return 'For Lookup';
				}
				switch(input.toUpperCase()){
					case 'C' : return 'For Cross-matching'; break;
					case 'R' : return 'Reserved'; break;
					case 'M' : return 'Incompatible'; break;
					case 'X' : return 'Returned to Storage'; break;
					case 'I' : return 'Issued'; break;
					case 'D' : return 'Discarded'; break;
					default  : return 'For Lookup';
				}
			}
		});
		var bloodRequestView = angular.module('BloodRequestView',['ngRoute','BloodRequestFilters'],function($interpolateProvider){
			$interpolateProvider.startSymbol('<%');
		    $interpolateProvider.endSymbol('%>');
		});


		var components = {{json_encode($item_components)}};
		var bloodRequest = {{json_encode($bloodRequest)}};
		var url_printSticker = "{{URL::to('BloodRequest/print/'.$bloodRequest->seqno)}}/";
		var url_previewCompatibilityTestResult = "{{URL::to('BloodRequest/previewCompatibilityTestResult/'.$bloodRequest->seqno.'/')}}";
		var url_printCompatibilityTestResult = "{{URL::to('BloodRequest/printCompatibilityTestResult/'.$bloodRequest->seqno.'/')}}";
		var url_blank = "{{URL::to('blank')}}";
		var url_viewSave = "{{URL::to('BloodUnit/viewSave')}}";
		var url_lookUpSave = "{{URL::to('BloodUnit/lookupSave')}}";
		var url_crossmatchSave = "{{URL::to('BloodUnit/crossmatchSave')}}";
		var url_returnUnit = "{{URL::to('BloodUnit/returnUnit')}}";
		var url_reserveRequest = "{{URL::to('BloodRequest/reserve')}}";
		var url_issueBloodUnits = "{{URL::to('BloodUnit/issueBloodUnits')}}";
		var url_verifyUnitForIssuance = "{{URL::to('BloodUnit/verifyUnitForIssuance')}}";
		var url_issuanceForm = "{{URL::to('BloodRequest/issuance')}}";
		var url_getDetailReactions = "{{URL::to('BloodUnit/detailReactions')}}";
		var url_saveDetailReactions = "{{URL::to('BloodUnit/saveDetailReactions')}}";
		var url_cancelPerform = "{{URL::to('BloodRequest/cancelPerform')}}";
		var user_id = "{{User::current()->user_id}}";
		var facility_cd = "{{User::current()->facility_cd}}";
		var bloodRequest = {{json_encode($bloodRequest)}};

		function getLookUpFrameURL(id,ids,component_cd){
			return "{{URL::to('BloodUnit')}}/"+id+"/"+ids+"/"+component_cd+"/{{$bloodRequest->blood_type}}";
		}

		function getDetails(){
			details = {{json_encode($js_details)}};
			for(i in details){
				prepareDetail(details[i]);
			}
			return details;
		}

		function prepareDetail(detail){
			detail.getDonationDate = function(){
					if(this.donation != null){
						if(this.donation.sched_id.toUpperCase() == 'WALK-IN'){
							return this.donation.created_dt;
						}else{
							return this.donation.mbd.donation_dt;
						}
					}
					return null;
				};
		}

	</script>
	{{HTML::script('js/controller/BloodRequestController.js')}}
	{{HTML::script('js/controller/LookUpController.js')}}
	{{HTML::script('js/controller/CrossmatchController.js')}}
	{{HTML::script('js/controller/ReleaseController.js')}}
	{{HTML::script('js/controller/InvestigateController.js')}}
	{{HTML::script('js/controller/CancelController.js')}}
@stop