<?php
	class EasyRadioButton extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyRadioButton($name,$attr,$properName,$default);
		}
		
		function render(){
			$this->items = array_key_exists('items', $this->attr) === true ? $this->attr['items'] : [];
			$this->attr = Easy::implodeAttrValues($this->attr);
			$this->attr['container_class'] = array_key_exists('container_class', $this->attr) === true ? $this->attr['container_class'] : null;
			$fields = array();
			foreach ($this->items as $key => $value) {
				$checked = $this->default == $key ? true : false;
				$title = array_key_exists('title', $this->attr) === true ? $this->attr['title'] : false;
				//dd($this->attr['items_attr']);
				$items_attr = array_key_exists('items_attr', $this->attr) === true ? $this->attr['items_attr'] : '';
				$fields[] = "<label class='".$this->attr['container_class']."' ".($title ? "title = '".$title."'" : "")." $items_attr >"./*Form::radio($this->name,$key,$checked)*/"<input type='radio' name='$this->name' value='$key' ".($checked ? 'checked' : '')." $items_attr />"." ".$value." </label> ";
			}

			return "<div >".implode('',$fields)."</div>";
		}
	}
?>