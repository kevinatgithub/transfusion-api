<?php

class APIController extends \BaseController {

	function test(){
		return Patient::take(5)->get();
	}

}