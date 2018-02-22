<?php
	class Province extends Eloquent{

		public $table = "rprov";

		public $timestamps = false;

		function region(){
			return $this->belongsTo('region','regcode','regcode');
		}

		function cities(){
			return $this->hasMany('city','provcode','provcode');
		}
	}
?>