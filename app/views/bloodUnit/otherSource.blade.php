<?php
	EasyRow::$global_label_container = "<label class='control-label col-sm-3'>?</label>"; 
	EasyRow::$global_attr = ['class' => 'form-group'];
	EasyRow::$validation = isset($validation) ? $validation : null;
	Easy::$global_attr = ['class' => 'form-control has-tooltip']; 
	$required_mark = "<b class='text-danger valign-middle' style='font-size:18px;'>*</b>";
?>
@section('content')
	<div ng:app="OtherSource" ng:controller="OtherSourceController">
		
		{{Form::open(['class' => 'form-horizontal'])}}
		<div class="row"><h3 class="col-xs-12 text-info">Outsourced Blood Unit</h3></div>
		<div class="row">
			<div class="col-sm-12">
				<a href="#" class="btn btn-danger btn-sm pull-right" onclick="parent.cancelLookUp()">Cancel</a>
				<input type="submit" class="btn btn-success btn-sm pull-right" style="margin-right:0.3em;" value="Save Changes" />
		     	<a href="{{URL::to('BloodUnit/'.$detail_id.'/'.$str_ids.'/'.$component->component_cd.'/'.$blood_type)}}" class="btn btn-primary btn-sm pull-right" style="margin-right:0.3em;"><span class="glyphicon glyphicon-arrow-left"></span> From In-house</a>    	
			</div>
		</div>
		<br/>
		<div class="row">
			<div class="col-sm-12">
				<legend>
					<div class="col-sm-1"></div>
					<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Blood Unit Details</div>
				</legend>
				{{EasyRow::make('Component '.$required_mark,[
					EasyText::make('comp_name',['parent_class' => 'col-sm-3','placeholder' => 'Component', 'readonly' => 'readonly'],null,$component->comp_name),
				])->render()}}

				{{EasyRow::make('Blood Type '.$required_mark,[
					EasyText::make('blood_type',['parent_class' => 'col-sm-3','placeholder' => 'Blood Type', 'readonly' => 'readonly'],null,$blood_type),
				])->render()}}

				{{EasyRow::make('New Serial No. '.$required_mark,[
					EasyText::make('donation_id',['parent_class' => 'col-sm-3','placeholder' => 'New Serial No.'],'New Serial No.')
				])->render()}}

				{{EasyRow::make('Serial No. from Source'.$required_mark,[
					EasyText::make('source_serial_no',['parent_class' => 'col-sm-3','placeholder' => 'Source Serial No.'],'Source Serial No')
				])->render()}}

				{{EasyRow::make('Date Collected '.$required_mark,[
					EasyDate::make('collected_dt',['parent_class' => 'col-sm-3','placeholder' => 'Date Collected'],'Date Collected'),
				])->render()}}				

				{{EasyRow::make('Expiration Date '.$required_mark,[
					EasyDate::make('expiration_dt',['parent_class' => 'col-sm-3','placeholder' => 'Expiration Date'],'Expiration Date'),
				])->render()}}

				{{EasyRow::make('Component Volume '.$required_mark,[
					EasyText::make('component_vol',['parent_class' => 'col-sm-3','placeholder' => 'Component Volume'],'Component Volume'),
				])->render()}}

				<legend>
					<div class="col-sm-1"></div>
					<div class="text-info"><span class="glyphicon glyphicon-info-sign"></span> Source Details</div>
				</legend>
				{{EasyRow::make('Blood Unit From '.$required_mark,[
					EasySelect::make('source_id',['parent_class' => 'col-sm-6','items' => $sources_items , 'ng:model' => 'source_id' , 'ng:change' => 'setSource()'])
				])->render()}}

				{{EasyRow::make('Facility Name '.$required_mark,[
					EasyText::make('facility_name',['parent_class' => 'col-sm-8','placeholder' => 'Facility Name', 'ng:model' => 'source.facility_name'],'Facility Name'),
				])->render()}}

				{{EasyRow::make('Type',[
					EasySelect::make('type',['parent_class' => 'col-sm-3', 'items' => $types_items,'placeholder' => 'Type', 'ng:model' => 'source.type']),
				])->render()}}

				{{EasyRow::make('Category',[
					EasySelect::make('category',['parent_class' => 'col-sm-3', 'items' => $categories_items,'placeholder' => 'Category' , 'ng:model' => 'source.category']),
				])->render()}}

				{{EasyRow::make('Contact Person',[
					EasyText::make('contact_person',['parent_class' => 'col-sm-3','placeholder' => 'Contact Person' , 'ng:model' => 'source.contact_person']),
					Form::label(null,'Designation',['class' => 'control-label col-sm-2']),
					EasyText::make('designation',['parent_class' => 'col-sm-3','placeholder' => 'Designation' , 'ng:model' => 'source.designation']),
				])->render()}}

				{{EasyRow::make('No., Street, Block',[
					EasyText::make('no_st_blk',['parent_class' => 'col-sm-8','placeholder' => 'No., Street, Block', 'ng:model' => 'source.no_st_blk']),
				])->render()}}

				{{EasyRow::make('Region',[
					EasySelect::make('regcode',['parent_class' => 'col-sm-3','placeholder' => 'Region' , 'id' => 'regcode' , 'ng:model' => 'source.regcode']),
				])->render()}}

				{{EasyRow::make('Province',[
					EasySelect::make('provcode',['parent_class' => 'col-sm-3','placeholder' => 'Province' , 'id' => 'provcode' , 'ng:model' => 'source.provcode']),
				])->render()}}

				{{EasyRow::make('City/Municipality',[
					EasySelect::make('citycode',['parent_class' => 'col-sm-3','placeholder' => 'City/Municipality' , 'id' => 'citycode' , 'ng:model' => 'source.citycode']),
				])->render()}}

				{{EasyRow::make('Barangay',[
					EasySelect::make('bgycode',['parent_class' => 'col-sm-3','placeholder' => 'Barangay' , 'id' => 'bgycode' , 'ng:model' => 'source.bgycode']),
				])->render()}}

				{{EasyRow::make('Zip Code',[
					EasyText::make('zipcode',['parent_class' => 'col-sm-3','placeholder' => 'Zip Code' , 'ng:model' => 'source.zipcode']),
				])->render()}}

				{{EasyRow::make('Telephone No.',[
					EasyText::make('tel_no',['parent_class' => 'col-sm-3','placeholder' => 'Telephone No.' , 'ng:model' => 'source.tel_no']),
				])->render()}}

				{{EasyRow::make('Mobile No.',[
					EasyText::make('mobile_no',['parent_class' => 'col-sm-3','placeholder' => 'Mobile No.' , 'ng:model' => 'source.mobile_no']),
				])->render()}}

				{{EasyRow::make('Fax No.',[
					EasyText::make('fax_no',['parent_class' => 'col-sm-3','placeholder' => 'Fax No.' , 'ng:model' => 'source.fax_no']),
				])->render()}}

				{{EasyRow::make('Email',[
					EasyText::make('email',['parent_class' => 'col-sm-5','placeholder' => 'Email Address', 'ng:model' => 'source.email']),
				])->render()}}

				
			</div>
			{{Form::close()}}
		</div>
	
	</div>
	{{HTML::script('js/lib/addressdropdown/addressdropdown.js')}}
	{{HTML::script('js/lib/jquery.datetimepicker/jquery.datetimepicker.js')}}
	{{HTML::style('js/lib/jquery.datetimepicker/jquery.datetimepicker.css')}}
	<script type="text/javascript">
		$(function(){
			AddressDropdown.config({server:'<?php echo URL::to('AddressDropdown') ?>'});
			AddressDropdown.implement();
		});

		var OtherSource = angular.module('OtherSource',[],function($interpolateProvider){
			$interpolateProvider.startSymbol('<%');
		    $interpolateProvider.endSymbol('%>');
		});

		function OtherSourceController($scope){
			$scope.source_id = '{{Input::get('source_id')}}';
			$scope.source = null;
			$scope.sources = {{json_encode($sources_js)}};

			if($scope.source_id != ''){
				$scope.source = $scope.sources[$scope.source_id];
				AddressDropdown.config({
					region_id:"regcode",
			        regcode: $scope.source.regcode != '' ? $scope.source.regcode : null,
			        prov_id:"provcode",
			        provcode: $scope.source.provcode != '' ? $scope.source.provcode : null,
			        city_id:"citycode",
			        citycode: $scope.source.citycode != '' ? $scope.source.citycode : null,
			        bgy_id:"bgycode",
			        bgycode: $scope.source.bgycode != '' ? $scope.source.bgycode : null
				});
			}

			$scope.setSource = function(){
				if($scope.source_id == ''){
					return;
				}
				$scope.source = $scope.sources[$scope.source_id];
				AddressDropdown.config({
					region_id:"regcode",
			        regcode: $scope.source.regcode != '' ? $scope.source.regcode : null,
			        prov_id:"provcode",
			        provcode: $scope.source.provcode != '' ? $scope.source.provcode : null,
			        city_id:"citycode",
			        citycode: $scope.source.citycode != '' ? $scope.source.citycode : null,
			        bgy_id:"bgycode",
			        bgycode: $scope.source.bgycode != '' ? $scope.source.bgycode : null
				});
				AddressDropdown.implement();
			};
		}

	</script>
@stop