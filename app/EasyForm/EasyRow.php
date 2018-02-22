<?php
	class EasyRow{

		var $fields = array();
		var $label = null;
		static public $validation = null;
		var $hasError = false;
		var $error_text = null;
		var $attr = array();
		static public $global_attr = array();
		static public $global_label_container = '?';
		
		function __construct($label = null,$fields = array(),$attr = array()){
			$this->fields = $fields;
			$this->label = $label;
			$this->attr = array_merge_recursive($attr,static::$global_attr);
			if(self::$validation != null){
				$messages = self::$validation->messages();
				foreach($this->fields as $i => $field){
					/*if(array_search($field->name, ['fname','mname','lname']) === false){
						dd($field);
					}*/
					if (is_object($field)) {
						$message = $messages->first($field->name);
						if(strlen($message) != 0){
							$field->hasError = true;
							$field->attr['title'] = str_replace(str_replace("_", " ", $field->name), $field->properName, $message);
								$this->error_text = str_replace(str_replace("_", " ", $field->name), $field->properName, $message);
							/*if(count($this->fields) == 1){
							}*/
							$this->fields[$i] = $field;
							$this->hasError = true;
						}
					    
					}else{
						$this->hasError = false;
					}
				}
			}

		}

		function render(){
			if(substr_count(self::$global_label_container, '?') === 1){
				$this->label = str_replace('?', $this->label, self::$global_label_container);
			}
			$this->attr = Easy::implodeAttrValues($this->attr);
			
			$view = array();
			foreach($this->fields as $field){
				if(method_exists($field, "render")){
					$parent_class = array_key_exists('parent_class', $field->attr) ? $field->attr['parent_class'] : 'col-sm-2';
					$view[] = "<div class='".$parent_class."'>".$field->render()."</div>";
				}else{
					$view[] = $field;
				}
			}

			$view = implode($view);

			$return = "<div class='".(array_key_exists('class', $this->attr) !== false ? $this->attr['class'] : null).' '.($this->hasError ? "has-error" : "")."'>";
			$return.= $this->label;
			$return.= $view;
			//$this->hasError ? $return.= "<div class='col-sm-2' style='padding-top:4px;'><p class='text-danger' >".$this->error_text."</p></div>" : null;
			/*if($this->hasError && array_key_exists('error-inline', $this->attr)){
				$return .= "<div class='col-sm-8'><p style='vertical-alignment:middle;' class='text-danger'>".$this->error_text."</p></div>";
			}elseif($this->hasError){
				$return .= "<div class='row'><div class='col-sm-8'><p class='text-danger'>".$this->error_text."</p></div></div>";
			}*/
			$return .= "</div>";
			return $return;
		}

		static function make($label = null,$fields = array(),$attr = array()){
			return new EasyRow($label,$fields,$attr);
		}
	}
?>