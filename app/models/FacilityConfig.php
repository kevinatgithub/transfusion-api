<?php
	class FacilityConfig extends Eloquent{

		public $table = 'r_bts_facility_config';
		public $primaryKey = 'facility_cd';
		public $timestamps = false;

		static function current(){
			$config = FacilityConfig::find(User::current()->facility_cd);
			if($config != null){
				return $config;
			}else{
				$config = new FacilityConfig();
				$config['facility_cd'] = User::current()->facility_cd;
				$config['auto_patient_id'] = 'N';
				$config['enable_patient_ward_no'] = 'Y';
				$config['enable_patient_room_no'] = 'Y';
				$config['enable_patient_bed_no'] = 'Y';
				return $config;
			}
		}

	}
?>