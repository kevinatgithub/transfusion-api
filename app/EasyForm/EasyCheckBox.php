<?php
	class EasyCheckBox extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyCheckBox($name,$attr,$properName,$default);
		}
		
		function render(){
			$this->items = array_key_exists('items', $this->attr) === true ? $this->attr['items'] : [];
			$this->attr = Easy::implodeAttrValues($this->attr);
			$this->attr['container_class'] = array_key_exists('container_class', $this->attr) === true ? $this->attr['container_class'] : '';
			$fields = array();
			foreach ($this->items as $key => $value) {
				$checked = $this->default == $key ? true : false;
				$title = array_key_exists('title', $this->attr) === true ? $this->attr['title'] : false;
				$fields[] = "<label class='".$this->attr['container_class']."' ".($title ? "title = '".$title."'" : "").">".Form::checkbox($this->name,$key,$checked)." ".$value." </label> ";
			}
			return "<div class=''>".implode('',$fields)."</div>";
		}
	}
?>