<?php
	class Physician extends Eloquent{

		public $table = 'bts_physician';
		public $timestamps = false;
		public $primaryKey = 'seqno';

		static function generateID($move = 0){
			$record = Physician::where('facility_cd','=',User::current()->facility_cd)->orderBy('physician_id','desc')->first();
			if($record != null){
				$last = $record->physician_id;
				// $last = str_replace(date('Y'), '', $last);
				// $last = str_replace('P', '', $last);
				$last = substr($last, 5);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,5);
			$new = date('Y').'P'.$last;
			$check = Physician::where('physician_id','=',$new)->where('facility_cd','=',User::current()->facility_cd)->first();
			while(count($check) != 0){
				return self::generateID($move++);
			}
			return $new;
		}


		static function generateSequenceNo($move = 0){
			$record = Physician::orderBy('seqno','desc')->first();
			if($record != null){
				$last = $record->seqno;
				$last = str_replace('NVBSP', '', $last);
				$last = str_replace(date('Y'), '', $last);
				$last++;
				$last+= $move;
			}else{
				$last = 1;
			}
			$last = addLeadingZero($last,7);
			$new = 'NVBSP'.date('Y').$last;
			$check = Physician::where('seqno','=',$new)->first();
			while(count($check) != 0){
				return self::generateSequenceNo($move++);
			}
			return $new;
		}

		static function setListFilter($filter){
			Session::put('physician_physician_name',$filter);
		}

		static function getListFilter(){
			return Session::get('physician_physician_name');
		}

		static function clearListFilter(){
			Session::pull('physician_physician_name');
		}

				function getBdate(){
			return $this->bdate != '' ? date('M d, Y',strtotime($this->bdate)) : '';
		}

		function getGender(){
			return strtoupper($this->gender) == 'M' ? 'Male' : (strtoupper($this->gender) == 'F' ? 'Female' : '');
		}

		function getFullName(){
			$full = ucwords($this->fname.' '.$this->mname.' '.$this->lname);
			if(strlen($this->name_suffix) != 0){
				$full .= ', '. $this->name_suffix;
			}
			return $full;
		}
	}