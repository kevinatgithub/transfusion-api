<?php
	class EasyText extends Easy{
		
		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyText($name,$attr,$properName,$default);
		}

		function render(){
			return Form::text($this->name,$this->default,Easy::implodeAttrValues($this->attr));
		}
	}
?>