<?php
	class BloodUnit extends Eloquent{

		public $table = "component";
		public $timestamps = false;
		public $primaryKey = 'donation_id';

		function donation(){
			return $this->hasOne('Donation','donation_id','donation_id');
		}

		
	}