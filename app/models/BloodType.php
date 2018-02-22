<?php
	class BloodType extends Eloquent{

		public $table = "r_bloodtype";
		public $timestamps = false;

		static function getList(){
			$blood_types = BloodType::all();
			$list = [];
			foreach($blood_types as $blood_type){
				$list[] = $blood_type->blood_type;
			}
			return $list;
		}
	}