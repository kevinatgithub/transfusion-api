<?php
	class BloodRequestDetails extends Eloquent{

		public $table = 'bts_blood_request_dtls';
		public $timestamps = false;
		public $primaryKey = 'id';

		function bloodRequest(){
			return $this->hasOne('BloodRequest','request_id','request_id')/*->where('facility_cd','=',$this->facility_cd)*/;
		}

		function component(){
			return $this->hasOne('Component','component_cd','component_cd');
		}

		function donation(){
			return $this->hasOne('Donation','donation_id','donation_id');
		}

		function bloodUnit(){
			return $this->hasOne('BloodUnit','donation_id','donation_id')->where('component_cd','=',$this->component_cd);
		}

		function source(){
			return $this->hasOne('OtherSource','source_id','source_id')->where('user_facility_cd','=',$this->facility_cd);
		}

		function reactions(){
			return $this->hasMany('DetailReaction','request_dtl_id','id');
		}

		static function generateID($move = 0,$request_id){
			$user = User::current();
			$request = BloodRequest::where('facility_cd','=',$user->facility_cd)->where('request_id','=',$request_id)->first();
			$record = BloodRequestDetails::where('facility_cd','=',$user->facility_cd)->where('request_id','=',$request_id)->orderBy('id','desc')->first();
			if($record != null){
				$last = $record->id;
				$last = str_replace($user->facility_cd, '', $last);
				$last = str_replace($request->seqno, '', $last);
				$last = str_replace('-', '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,2);
			$new = $request->seqno.'-'.$last;
			$check = BloodRequestDetails::where('id','=',$new)->where('facility_cd','=',$user->facility_cd)->first();
			while(count($check) != 0){
				return self::generateID($move++,$request_id);
			}
			return $new;
		}

		function scopeSelf($query){
			return $query->whereFacilityCd(User::current()->facility_cd);
		}
	}