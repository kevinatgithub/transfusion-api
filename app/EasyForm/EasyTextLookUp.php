<?php
	class EasyTextLookUp extends Easy{
		
		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyTextLookUp($name,$attr,$properName,$default);
		}

		function render(){
			$parent_class = array_key_exists('parent_class', $this->attr) ? $this->attr['parent_class'] : 'col-sm-12';
			$btn_attr = array_key_exists('btn_attr', $this->attr) ? $this->attr['btn_attr'] : [];
			return '<div class="'.$parent_class.' input-group">'.
				EasyText::make($this->name,$this->attr)->render().
				'<div class="input-group-btn ">'.
					EasyButton::make('select_'.$this->name,$btn_attr,'<span class="glyphicon glyphicon-search"></span>')->render().
				'</div>'.
			'</div>';
			//return Form::text($this->name,$this->default,Easy::implodeAttrValues($this->attr));
		}
	}
?>