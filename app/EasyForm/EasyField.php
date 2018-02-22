<?php
	interface EasyField{
		
		function __construct($name,$properName,$attr = array(),$default = null);

		static function make($name,$properName,$attr = array(),$default = null);

		function render();

	}