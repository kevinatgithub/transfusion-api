<?php
	class TransfusionReaction extends Eloquent{
		
		public $table = "r_bts_transfusion_reaction";
		public $timestamps = false;


		function detailReaction(){
			return $this->hasOne('DetailReaction','reaction_id','reaction_id');
		}

		static function getList(){
			$reactions = TransfusionReaction::where('disable_flg','=','N')->get();
			$list = [];
			foreach($reactions as $reaction){
				$list[$reaction->reaction_id] = $reaction->type;
			}
			return $list;
		}
	}