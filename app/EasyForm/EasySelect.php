<?php
	class EasySelect extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasySelect($name,$attr,$properName,$default);
		}
		
		function render(){
			$this->attr['items'] = array_key_exists('items', $this->attr) === true ? $this->attr['items'] : ['' => 'Please Select'];
			return Form::select($this->name,$this->attr['items'],$this->default,Easy::implodeAttrValues($this->attr));
		}
	}
?>