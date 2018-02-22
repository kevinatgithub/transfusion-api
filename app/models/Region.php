<?php
	class Region extends Eloquent{

		public $table = 'rregion';

		public $timestamps = false;

		function provinces(){
			return $this->hasMany('Province','regcode','regcode');
		}
	}
?>