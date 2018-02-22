<?php


class PasswordGrantVerifier extends \BaseController {

	public function verify($username, $password){

		// header("Access-Control-Allow-Origin: *");
		// header('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
		$credentials = [
			'user_id'    => $username,
			'password' => $password,
		  ];

		  if (Auth::once($credentials)) {
			Auth::user()->user_id;
		  }
	
		  return false;
	}

}