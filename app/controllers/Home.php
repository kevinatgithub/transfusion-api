<?php
class Home extends BaseController{

	public $restful = true;
	public $layout = 'layout.default';

	function setNav(){
		if(User::guest()){
			$this->layout->nav = View::make('layout.nav_public');
		}else{
			$this->layout->nav = View::make('layout.nav_private');
		}
	}

	function get_index(){
		return Redirect::to('BloodRequest');
	}

	function get_admin(){
		$this->setNav();
	}

	function login(){
		$this->setNav();
		$data = Input::all();
		$validation = null;
		$general_failure = null;
		if(count($data)){
			$validation = Validator::make($data,[
					'user_id' => 'required',
					'password' => 'required'
				]);
			if(!$validation->fails()){
				if(User::attemptLogin($data)){
					return Redirect::to('redirect');
				}else{
					$general_failure = 'Login Failed, Please check User ID and Password';
				}
			}
		}
		$this->layout->content = View::make('login',['validation' => $validation,'general_failure' => $general_failure]);
	}
}