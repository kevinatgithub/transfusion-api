<?php
	class EasyTime extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyTime($name,$attr,$properName,$default);
		}

		function render(){
			return "<div class='input-append bootstrap-timepicker'>".
						EasyText::make($this->name,$this->attr,$this->properName,$this->default)->render().
						"<span class='add-on'><i class='icon-time'></i></span>
			        </div><script type='text/javascript'>
			        	$(function(){
			        		$('[name=$this->name]').timepicker()
			        	});
			        </script>";
		}

	}
?>