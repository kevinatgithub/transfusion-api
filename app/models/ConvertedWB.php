<?php
	class ConvertedWB extends Eloquent{

		public $table = "converted_wb";
		public $primary = "id";
		public $timestamps = false;

		function unit(){
			return $this->hasOne('BloodUnit','donation_id','donation_id')->where('component_cd','=',$this->component_cd);
		}

	}