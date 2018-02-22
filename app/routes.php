<?php

Route::post('token', function() {
    return Response::json(Authorizer::issueAccessToken());
});

Route::group(['before' => 'oauth'],function(){
	Route::controller('api','APIController');
});

Route::get('blank',function(){
	return View::make('layout.blank');
});
Route::get('login','Home@login');
Route::post('login','Home@login');
Route::get('logout',function(){
	User::logout();
	return Redirect::to('login');
});


Route::get('redirect',function(){
	if(User::guest()){
		return Redirect::to('login');
	}else{
		$user = User::current();
		switch($user->ulevel){
			case -1:
			case 7:
				return Redirect::to('BloodRequest');
				
			break;
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 6:
				return Redirect::to('BloodRequest');
			break;
		}
	}
});

Route::group(array('before' => 'CheckAccess'),function(){
	Route::controller('Census', 'ReportController');
	Route::get('BloodRequest/previewCompatibilityTestResult/{seqno}/{detail_id}/{type}','BloodRequestController@get_previewCompatibilityTestResult');
	Route::post('BloodRequest/previewCompatibilityTestResult/{seqno}/{detail_id}/{type}','BloodRequestController@post_previewCompatibilityTestResult');
	Route::get('BloodRequest/printCompatibilityTestResult/{seqno}/{detail_id}/{type}','BloodRequestController@get_printCompatibilityTestResult');
	Route::post('BloodUnit/saveDetailReactions','BloodUnitController@post_saveDetailReactions');
	Route::post('BloodUnit/detailReactions','BloodUnitController@post_detailReactions');
	Route::controller('TransfusionReaction','TransfusionReactionController');
	Route::get('BloodRequest/issuance/{seqno}','BloodRequestController@get_issuanceForm');
	Route::post('BloodRequest/reserve','BloodRequestController@post_reserveBloodRequest');
	Route::post('BloodRequest/cancelPerform','BloodRequestController@post_cancelBloodRequest');
	Route::get('BloodRequest/print/{seqno}/{detail_id}/{type}','BloodRequestController@get_print');
	Route::post('BloodUnit/verifyUnitForIssuance','BloodUnitController@post_verifyUnitForIssuance');
	Route::post('BloodUnit/issueBloodUnits','BloodUnitController@post_issueBloodUnits');
	Route::post('BloodUnit/returnUnit','BloodUnitController@post_returnUnit');
	Route::post('BloodUnit/viewSave','BloodUnitController@post_viewSave');
	Route::post('BloodUnit/crossmatchSave','BloodUnitController@post_crossmatchSave');
	Route::post('BloodUnit/lookupSave','BloodUnitController@post_lookupSave');
	Route::post('BloodUnit/{i}/{d}/{c}/{b}/otherSource','BloodUnitController@post_otherSource');
	Route::get('BloodUnit/{i}/{d}/{c}/{b}/otherSource','BloodUnitController@get_otherSource');
	Route::get('BloodUnit/{i}/{d}/{c}/{b}/clearFilter','BloodUnitController@get_clearFilter');
	Route::post('BloodUnit/{i}/{d}/{c}/{b}','BloodUnitController@post_index');
	Route::get('BloodUnit/{i}/{d}/{c}/{b}','BloodUnitController@get_index');
	Route::controller('BloodUnit','BloodUnitController');
	Route::controller('AddressDropdown','AddressDropdownController');
	Route::get('BloodRequestPhysician/{any}/edit','BloodRequestPhysicianController@get_edit');
	Route::post('BloodRequestPhysician/{any}/edit','BloodRequestPhysicianController@post_edit');
	Route::controller('BloodRequestPhysician','BloodRequestPhysicianController');
	Route::get('BloodRequestPatient/{any}/edit','BloodRequestPatientController@get_edit');
	Route::post('BloodRequestPatient/{any}/edit','BloodRequestPatientController@post_edit');
	Route::controller('BloodRequestPatient','BloodRequestPatientController');
	Route::get('Physician/{any}/edit','PhysicianController@get_edit');
	Route::post('Physician/{any}/edit','PhysicianController@post_edit');
	Route::controller('Physician','PhysicianController');
	Route::get('Patient/{any}/edit','PatientController@get_edit');
	Route::post('Patient/{any}/edit','PatientController@post_edit');
	Route::controller('Patient','PatientController');
	Route::get('BloodRequest/{any}/view','BloodRequestController@get_viewRequest');
	Route::controller('BloodRequest','BloodRequestController');
	Route::controller('/','Home');
});

Route::filter('CheckAccess',function(){
	if(User::guest()){
		return Redirect::to('login');
	}
});

Event::listen('laravel.query',function($sql){
	var_dump($sql);
});


function addLeadingZero($str,$len){
	while(strlen($str) < $len){
		$str = '0'.$str;
	}
	return $str;
}

function getGender($gender_code){
	switch(strtoupper($gender_code)){
		case 'M':
			return 'Male';
		case 'F':
			return 'Female';
		default:
			return null;
	}
}

function getFormatedDate($date,$short = true, $time = false){
	if($date != null && $date != ''){
		if($short){
			return date('M d, Y',strtotime($date));
		}elseif(!$short && !$time){
			return date('F d, Y',strtotime($date));
		}elseif($short && $time){
			return date('M d, Y H:i:s',strtotime($date));
		}elseif(!$short && $time){
			return date('F d, Y H:i:s',strtotime($date));
		}
	}
	return '';
}

function PageTitle($txt){
	return '<div class="pull-right" style="margin-right:1em;">
				<p class="text-info row-valign-middle"><span class="glyphicon glyphicon-info-sign"></span>&nbsp;<b>'.$txt.'</b></p>
			</div>';
}

function PrepareJSValue($name,$value){
	$submit = Input::get($name);
	if($submit != null){
		return $submit;
	}
	if($value != null || $value != ""){
		return "'".$value."'";
	}else{
		return "null";
	}
}


function checkVerifier($verifier){
	$user = User::current();
	if($user->user_id == $verifier['user_id']){
		return false;
	}
	$checkRecord = User::where('user_id','=',$verifier['user_id'])->where('password','=',md5($verifier['password']))->where('facility_cd','=',$user->facility_cd)->first();
	if($checkRecord == null){
		return false;
	}
	return true;
}

Event::listen('illuminate.query', function($query){
    // print($query).'<br/><br/>';
});