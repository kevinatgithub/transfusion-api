<?php
	class DetailReaction extends Eloquent{

		public $table = "bts_reactions";
		public $timestamps = false;

		function transfusionReaction(){
			return $this->hasOne('TransfusionReaction','reaction_id','reaction_id');
		}

		function detail(){
			return $this->hasOne('BloodRequestDetails','id','request_dtl_id');
		}
	}