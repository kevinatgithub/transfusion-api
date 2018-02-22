<?php
	class EasyDateTime extends Easy{

		static function make($name,$attr = array(),$properName = null,$default = null){
			return new EasyDateTime($name,$attr,$properName,$default);
		}

		function render(){
			return "<div id='datetime_".$this->name."' class='input-append'>".
						EasyText::make($this->name,$this->attr,$this->properName,$this->default)->render().
						"<span class='add-on'><i class='icon-time' data-date-icon='icon-calendar'></i></span>
			        </div><script type='text/javascript'>
			        	$(function(){
			        		$('[name=".$this->name."]').datetimepicker({dateFormat:'yy-mm-dd'});
			        	});
			        </script>";
		}

	}
?>