<?php
	class EasyPassword extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyPassword($name,$attr,$properName,$default);
		}
		
		function render(){
			return Form::password($this->name,Easy::implodeAttrValues($this->attr));
		}

	}
?>