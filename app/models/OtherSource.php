<?php
	class OtherSource extends Eloquent{

		public $table = "bts_other_source";
		public $timestamps = false;
		public $primaryKey = "seqno";

		static function generateID($move = 0){
			$record = OtherSource::where('user_facility_cd','=',User::current()->facility_cd)->orderBy('source_id','desc')->first();
			if($record != null){
				$last = $record->source_id;
				$last = str_replace(date('Y'), '', $last);
				$last = str_replace('S', '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,5);
			$new = date('Y').'S'.$last;
			$check = OtherSource::where('user_facility_cd','=',User::current()->facility_cd)->where('source_id','=',$new)->first();
			while(count($check) != 0){
				return self::generateID($move++);
			}
			return $new;
		}

		static function generateSequenceNo($move = 0){
			$record = OtherSource::orderBy('seqno','desc')->first();
			if($record != null){
				$last = OtherSource::orderBy('seqno','desc')->first()->seqno;
				$last = str_replace('NVBSP', '', $last);
				$last = str_replace(date('Y'), '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,7);
			$new = 'NVBSP'.date('Y').$last;
			$check = OtherSource::where('seqno','=',$new)->first();
			while(count($check) != 0){
				return self::generateSequenceNo($move++);
			}
			return $new;
		}
	}