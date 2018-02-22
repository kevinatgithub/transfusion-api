<?php
	class EasyHidden extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyHidden($name,$attr,$properName,$default);
		}
		
		function render(){
			return Form::hidden($this->name,$this->default,Easy::implodeAttrValues($this->attr));
		}

	}
?>