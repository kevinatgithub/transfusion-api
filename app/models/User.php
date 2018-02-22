<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'r_user';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function facility(){
		return $this->hasOne('Facility','facility_cd','facility_cd');
	}

	public static function guest(){
		if(Session::has('CurrentUser')){
			return false;
		}else{
			return true;
		}
	}

	public static function attemptLogin($credentials){
		$user = DB::table('r_user')->where('user_id','=',$credentials['user_id'])
									   ->where('password','=',md5($credentials['password']))
									   ->first();
		if($user != null){
			static::login($user);
			return $user;
		}
		return false;
	}

	public static function login($user){
		Session::put('CurrentUser',$user);
	}

	public static function logout(){
		return Session::pull('CurrentUser');
	}

	public static function current(){
		return Session::get('CurrentUser',false);
	}

	function getBdate(){
		return $this->bdate != '' ? date('M d, Y',strtotime($this->bdate)) : '';
	}

	function getGender(){
		return strtoupper($this->gender) == 'M' ? 'Male' : (strtoupper($this->gender) == 'F' ? 'Female' : '');
	}

	function getFullName(){
		return ucwords($this->user_fname.' '.$this->user_mname.' '.$this->user_lname);
	}

}
