<?php
	class Patient extends Eloquent{

		public $table = 'bts_patient';
		public $timestamps = false;
		public $primaryKey = 'seqno';

		public function bloodRequests(){
			return $this->hasMany('BloodRequest','patient_id','patient_id');
		}

		static function generateID($move = 0){
			$record = Patient::where('facility_cd','=',User::current()->facility_cd)->orderBy('patient_id','desc')->first();
			if($record != null){
				$last = $record->patient_id;
				$last = str_replace(date('Y'), '', $last);
				$last = str_replace('P', '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,5);
			$new = date('Y').'P'.$last;
			$check = Patient::where('patient_id','=',$new)->where('facility_cd','=',User::current()->facility_cd)->first();
			while(count($check) != 0){
				return self::generateID($move++);
			}
			return $new;
		}


		static function generateSequenceNo($move = 0){
			$record = Patient::orderBy('seqno','desc')->first();
			if($record != null){
				$last = $record->seqno;
				$last = substr($last,9,strlen($last));
				//$last = str_replace('NVBSP', '', $last);
				//$last = str_replace(date('Y'), '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			//$last = addLeadingZero($last,7);
$last = str_pad($last, 7, '0', STR_PAD_LEFT);

			$new = 'NVBSP'.date('Y').$last;
			$check = Patient::where('seqno','=',$new)->first();
//dd($check);
			while(count($check) != 0){
				return self::generateSequenceNo($move++);
			}
			return $new;
		}

		static function setListFilter($filter){
			Session::put('patient_patient_name',$filter);
		}

		static function getListFilter(){
			return Session::get('patient_patient_name');
		}

		static function clearListFilter(){
			Session::pull('patient_patient_name');
		}

		function getBdate(){
			return $this->bdate != '' ? date('M d, Y',strtotime($this->bdate)) : '';
		}

		function getGender(){
			return strtoupper($this->gender) == 'M' ? 'Male' : (strtoupper($this->gender) == 'F' ? 'Female' : '');
		}

		function getFullName(){
			$full = ucwords($this->lname.', '.$this->fname.' '.$this->mname);
			if(strlen($this->name_suffix) != 0){
				$full .= ', '. $this->name_suffix;
			}
			return $full;
		}

		static function getRules(){
			Validator::extend('patient_id_unique_locally', function($attribute, $value, $parameters){
			    //return $value == 'foo';
			    $patient = Patient::where('facility_cd','=',User::current()->facility_cd)->where('patient_id','=',$value)->first();
			    return $patient == null;
			});
			$rules = [
					'fname' => 'required',
					'lname' => 'required',
					'bdate' => 'required',
					'gender' => 'required'
				];
			$config = FacilityConfig::current();
			if($config != null){
				if($config->auto_patient_id == 'N'){
					$rules['patient_id'] = 'required|patient_id_unique_locally';
				}
			}
			return $rules;
		}

		static function getCustomMessages(){
			return [
					'patient_id_unique_locally' => 'The :attribute has already been taken.'
				];
		}
	}
?>