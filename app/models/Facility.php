<?php
	class Facility extends Eloquent{

		public $table = "r_facility";
		public $timestamps = false;
		public $primaryKey = "facility_cd";

		function users(){
			return $this->hasMany('User','facility_cd','facility_cd');
		}

		function region(){
			return $this->hasOne('Region','regcode','address_region');
		}

		function province(){
			return $this->hasOne('Province','provcode','address_prov');
		}

		function city(){
			return $this->hasOne('City','citycode','address_citymun');
		}

		function barangay(){
			return $this->hasOne('Barangay','bgycode','address_bgy');
		}

		function getAddress(){
			$address = array();

			if(strlen($this->address_no_st_blk) > 0){
				$address[] = $this->address_no_st_blk;
			}

			$brgy = $this->barangay;
			if($brgy != null){
				$address[] = $brgy->bgyname;
			}

			$city = $this->city;
			if($city != null){
				$address[] = $city->cityname;
			}

			$province = $this->province;
			if($province != null){
				$address[] = $province->provname;
			}

			$region = $this->region;
			if($region != null){
				$address[] = $region->regname;
			}

			return implode(", ", $address);
		}

		static function getList(){
			$facilities = Facility::where('disable_flg','=','N')->get();
			$list = [];
			foreach($facilities as $facility){
				$list[$facility->facility_cd] = $facility->facility_name;
			}
			return $list;
		}
	}