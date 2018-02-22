<?php
	class City extends Eloquent{

		public $table = 'rcitymun';

		public $timestamps = false;

		function region(){
			return $this->belongsTo('region','regcode','regcode');
		}

		function province(){
			return $this->belongsTo('province','provcode','provcode');
		}

		function barangays(){
			return $this->hasMany('barangay','citycode','citycode');
		}
	}
?>