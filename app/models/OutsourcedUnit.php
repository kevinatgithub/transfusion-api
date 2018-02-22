<?php
class OutsourcedUnit extends Eloquent{

	public $table = "outsourced_component";
	public $timestamps = false;
	public $primary = "donation_id";

	function sourceFacility(){
		return $this->hasOne('Facility','facility_cd','source');
	}
}