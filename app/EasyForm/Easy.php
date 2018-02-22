<?php
	class Easy implements EasyField{

		var $name = null;
		var $properName = null;
		var $default = null;
		var $hasError = false;
		static public $global_attr = array();
		var $attr;
		static public $global_default_object = null;

		function __construct($name,$attr = array(),$properName = null,$default = null){
			$this->name = $name;
			$this->properName = $properName;
			$this->default = $default;
			if(($default = Input::get($this->name)) !== null){
				$this->default = $default;
			}else if(self::$global_default_object != null){
				$field = $this->name;
				$this->default = @self::$global_default_object->$field;
			}
			$this->attr = array_merge_recursive($attr,static::$global_attr);

			$input = Input::get($name);
			if($input != null){
				$this->default = $input;
			}
		}

		function render(){
			//return Form::text($this->name,$this->default,$this->attr);
			return 'Please override method render() in Field';
		}

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new Easy($name,$attr,$properName,$default);
		}

		static function implodeAttrValues($attr){
			foreach($attr as $key => $value){
				if(is_array($value)){
					$attr[$key] = implode(' ',$value);
				}
			}
			return $attr;
		}

		static function arrayToAttribute($arr){
			$out = [];
			foreach ($arr as $key => $value) {
				$out[] = $key.' = "'.$value.'"';
			}
			return implode(' ',$out);
		}
	}
?>