<?php
	class Barangay extends Eloquent{

		public $table = 'rbrgy';

		public $timestamps = false;

		function region(){
			return $this->belongsTo('region','regcode','regcode');
		}

		function province(){
			return $this->belongsTo('province','provcode','provcode');
		}

		function city(){
			return $this->belongsTo('city','citycode','citycode');
		}
	}
?>