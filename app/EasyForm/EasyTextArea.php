<?php
	class EasyTextArea extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyTextArea($name,$attr,$properName,$default);
		}

		function render(){
			return Form::textarea($this->name,$this->default,Easy::implodeAttrValues($this->attr));
		}
	}
?>