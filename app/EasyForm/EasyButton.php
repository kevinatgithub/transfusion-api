<?php
	class EasyButton extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyButton($name,$attr,$properName,$default);
		}

		function render(){
			return Form::button($this->properName,Easy::implodeAttrValues($this->attr));
		}

	}
?>