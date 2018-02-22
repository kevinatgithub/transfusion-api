<?php
class BloodRequest extends Eloquent{

	public $table = 'bts_blood_request';
	public $timestamps = false;
	public $primaryKey = 'seqno';

	function patient(){
		return $this->hasOne('Patient','patient_id','patient_id')->where('facility_cd','=',User::current()->facility_cd);
	}

	function physician(){
		return $this->hasOne('Physician','physician_id','physician_id')->where('facility_cd','=',User::current()->facility_cd);
	}

	function details(){
		return $this->hasMany('BloodRequestDetails','request_id','request_id')->where('facility_cd','=',$this->facility_cd)->where('disable_flg','=','N');
	}

	function verifier(){
		return $this->hasOne('User','user_id','crossmatch_verified_by');
	}

	function bloodType(){
		return $this->hasOne('BloodType','blood_type','blood_type');
	}

	static function generateID($move = 0){
		$record = BloodRequest::where('facility_cd','=',User::current()->facility_cd)->where('request_id','like',date('Y').'%')->orderBy('request_id','desc')->first();
		if($record != null){
			$last = $record->request_id;
			// $last = str_replace(date('Y'), '', $last);
			$last = substr($last, 4,strlen($last));
			$last = str_replace('R', '', $last);
			$last++;
			$last+= $move;

		}else{
			$last = 1;
		}
			
		$last = addLeadingZero($last,5);
		$new = date('Y').'R'.$last;
		$check = BloodRequest::where('request_id','=',$new)->where('facility_cd','=',User::Current()->facility_cd)->first();
		while(count($check) != 0){
			return self::generateID($move++);
		}
		return $new;
	}

	static function generateSequenceNo($move = 0){
		$record = BloodRequest::where('created_dt','like',date('Y').'%')->orderBy('seqno','desc')->first();
		if($record != null){
			$last = BloodRequest::where('created_dt','like',date('Y').'%')->orderBy('seqno','desc')->first()->seqno;
			$last = substr($last,9,strlen($last));
			$last++;
			$last+= $move;
		}else{
			$last = 1;
		}
		$last = addLeadingZero($last,7);
		$new = 'NVBSP'.date('Y').$last;
		$check = BloodRequest::where('seqno','=',$new)->first();
		while(count($check) != 0){
			return self::generateSequenceNo($move++);
		}
		return $new;
	}

	static function getStatusValue($status,$plain = true){
		switch (strtoupper($status)) {
			case 'Q':
				return $plain ? 'Queued' : '<b class="text-warning">Queued</b>';
				break;
			case 'R':
				return $plain ? 'Reserved' : '<b class="text-info">Reserved</b>';
				break;
			case 'I':
				return $plain ? 'Issued' : '<b class="text-success">Issued</b>';
				break;
			case 'C':
				return $plain ? 'Cancelled' : '<b class="text-danger">Cancelled</b>';
				break;
			default:
				return '';
				break;
		}
	}

	static function getListFilter(){
		$filters = [];
		if(($donation_id = Session::get('BloodRequest_donation_id')) !== null){
			$filters['donation_id'] = $donation_id;
		}
		if(($request_id = Session::get('BloodRequest_request_id')) !== null){
			$filters['request_id'] = $request_id;
		}
		if(($patient_id = Session::get('BloodRequest_patient_id')) !== null){
			$filters['patient_id'] = $patient_id;
		}
		if(($patient_name = Session::get('BloodRequest_patient_name')) !== null){
			$filters['patient_name'] = $patient_name;
		}
		if(($physician_name = Session::get('BloodRequest_physician_name')) !== null){
			$filters['physician_name'] = $physician_name;
		}
		return $filters;
	}

	static function setListFilters($data){
		if(isset($data['request_id'])){
			if($data['request_id'] != ''){
				Session::put('BloodRequest_request_id',$data['request_id']);
			}
		}
		if($data['donation_id'] != ''){
			Session::put('BloodRequest_donation_id',$data['donation_id']);
		}
		if($data['patient_id'] != ''){
			Session::put('BloodRequest_patient_id',$data['patient_id']);
		}
		if($data['patient_name'] != ''){
			Session::put('BloodRequest_patient_name',$data['patient_name']);
		}
		if($data['physician_name'] != ''){
			Session::put('BloodRequest_physician_name',$data['physician_name']);
		}
	}

	static function clearListFilter(){
		Session::pull('BloodRequest_request_id');
		Session::pull('BloodRequest_donation_id');
		Session::pull('BloodRequest_patient_id');
		Session::pull('BloodRequest_patient_name');
		Session::pull('BloodRequest_physician_name');
	}

	static function getRules(){
		$config = FacilityConfig::current();
		$rules = [
				'patient_id' => 'required',
				'patient_care' => 'required',
				'physician_name' => 'required',
				'diagnosis' => 'required',
				'hemo_level' => 'required|numeric',
				'blood_type' => 'required'
			];
		if($config != null){
			if($config->enable_patient_ward_no == 'Y'){
				$rules['ward_no'] = "required";
			}
			if($config->enable_patient_room_no == 'Y'){
				$rules['room_no'] = "required";
			}
			if($config->enable_patient_bed_no == 'Y'){
				$rules['bed_no'] = 'required';
			}
		}
		return $rules;
	}

	static function eagerLoading(){
		$user = User::current();
		return BloodRequest::whereDisableFlg('N')
					->with('Patient','Physician')
					->where(function($t){
							$filters = BloodRequest::getListFilter();
							if(count($filters) != 0){
								if(array_key_exists('request_id', $filters) !== false){
									$t->where('request_id','=',trim($filters['request_id']));
								}

								if(array_key_exists('patient_id', $filters) !== false){
									$t->where('patient_id','=',trim($filters['patient_id']));
								}
							}
						})
					->whereFacilityCd($user->facility_cd)
					->orderBy('request_id','DESC')
					->groupBy('seqno')
					->paginate(15);
	}

	static function nonEagerLoading(){
		$user = User::current();
		$filters = BloodRequest::getListFilter();
		if(count($filters) == 0){
			return BloodRequest::with('Patient','Physician')->whereFacilityCd($user->facility_cd)->whereDisableFlg('N')->orderBy('bts_blood_request.request_id','DESC')->groupBy("bts_blood_request.seqno")->paginate(15);
		}

		return BloodRequest::join('bts_patient','bts_blood_request.patient_id','=','bts_patient.patient_id')
								->join('bts_physician','bts_blood_request.physician_id','=','bts_physician.physician_id')
								->where('bts_blood_request.facility_cd' , '=' , $user->facility_cd)
								->where('bts_blood_request.disable_flg','=','N')
								->where(function($t){
									$filters = BloodRequest::getListFilter();

									if(count($filters) != 0){
										if(array_key_exists('request_id', $filters) !== false){
											$t->where('bts_blood_request.request_id','=',trim($filters['request_id']));
										}

										if(array_key_exists('patient_id', $filters) !== false){
											$t->where('bts_patient.patient_id','=',trim($filters['patient_id']));
										}

										if(array_key_exists('patient_name', $filters) !== false){
											$t->orwhere('bts_patient.fname','like',"%".trim($filters['patient_name'])."%");
											$t->orwhere('bts_patient.mname','like',"%".trim($filters['patient_name'])."%");
											$t->orwhere('bts_patient.lname','like',"%".trim($filters['patient_name'])."%");
										}

										if(array_key_exists('physician_name', $filters) !== false){
											$t->orwhere('bts_physician.fname','like',"%".trim($filters['physician_name'])."%");
											$t->orwhere('bts_physician.mname','like',"%".trim($filters['physician_name'])."%");
											$t->orwhere('bts_physician.lname','like',"%".trim($filters['physician_name'])."%");
										}
								}
			})->orderBy('bts_blood_request.request_id','DESC')->groupBy("bts_blood_request.seqno")->paginate(15);
	}

	static function searchByDonationID(){
		$filters = BloodRequest::getListFilter();

		$details = BloodRequestDetails::whereDonationId($filters['donation_id'])->get();

		$request_ids = [];
		foreach($details as $detail){
			$request_ids[] = $detail->request_id;
		}
		if(count($request_ids)){
			return BloodRequest::with('Patient','Physician')->whereIn('request_id',$request_ids)->paginate(15);
		}else{
			return BloodRequest::with('Patient','Physician')->whereRequestId(false)->paginate(15);
		}
	}

	function scopeSelf($query){
		return $query->whereFacilityCd(User::current()->facility_cd);
	}

}