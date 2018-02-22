<?php
	class Donation extends Eloquent{

		public $table = 'donation';
		public $timestamps = false;
		public $primaryKey = 'seqno';

		function bloodUnits(){
			return $this->hasMany('BloodUnit','donation_id','donation_id');
		}

		function mbd(){
			return $this->hasOne('MBD','sched_id','sched_id');
		}

		function getDonationDate(){
			if(strtoupper($this->sched_id) == 'WALK-IN'){
				return $this->created_dt;
			}else{
				return $this->mbd->donation_dt;
			}
		}

		function donor(){
			return $this->hasOne('Donor','seqno','donor_sn');
		}
	}