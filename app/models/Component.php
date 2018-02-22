<?php
	class Component extends Eloquent{

		public $table = 'r_component';
		public $timestamps = false;

		static function getList(){
			$components = Component::where("disable_flg",'=','N')->get();
			$list =  [];
			foreach($components as $component){
				$list[$component->component_cd] = $component->comp_name;
			}
			return $list;
		}
	}