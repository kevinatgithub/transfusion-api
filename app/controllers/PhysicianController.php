<?php
	class PhysicianController extends BaseController{

		public $restful = true;
		public $layout = 'layout.default';

		function setNav($currentPage = null){
			$currentPage = 'Physician';
			if(User::guest()){
				$this->layout->nav = View::make('layout.nav_public');
			}else{
				$this->layout->nav = View::make('layout.nav_private',['currentPage' => $currentPage]);
			}
		}

		function get_index(){
			$this->setNav();
			$user = User::current();
			$physicians = Physician::where('facility_cd','=',$user->facility_cd)
							->where('disable_flg','=','N')
							->where(function($t){
				$filter = Physician::getListFilter();
				if($filter != null){
					$t->orwhere('fname','like','%'.$filter.'%');
					$t->orwhere('mname','like','%'.$filter.'%');
					$t->orwhere('lname','like','%'.$filter.'%');

				}
				
			})->paginate(15);
			$this->layout->content = View::make('physician.list',[
					'physicians' => $physicians
				]);
		}

		function get_clearfilter(){
			//Clear Session Filters
			Physician::clearListFilter();
			return Redirect::to('Physician');
		}

		function post_index(){
			$user = User::current();
			$physician_name = Input::get('physician_name');
			if($physician_name != null){
				Physician::setListFilter($physician_name);
				return Redirect::to('Physician');
			}else{
				$indexes = Input::get('index');
				foreach($indexes as $index){
					//Physician::where('physician_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->delete();
					$physician = Physician::where('physician_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->first();
					$physician->disable_flg = 'Y';
					$physician->save();
				}
				return Redirect::to('Physician');
				
			}
		}

		function get_create($validation = null){
			$this->setNav();
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}
			$this->layout->content = View::make('physician.form',['nationalities' => $nationalities,'validation' => $validation]);
		}


		function post_create(){
			$data = Input::all();
			$validation = Validator::make($data,[
					'license_no' => 'required',
					'fname' => 'required',
					'lname' => 'required',
					'bdate' => 'required',
					'gender' => 'required'
				]);
			if(!$validation->fails()){
				$user = User::current();
				unset($data['_token']);
				$data['physician_id'] = Physician::generateID();
				$data['seqno'] = Physician::generateSequenceNo();
				$data['facility_cd'] = $user->facility_cd;
				$data['created_by'] = $user->user_id;
				$data['created_dt'] = date('Y-m-d H:i:s');
				Physician::insert($data);
				return Redirect::to('Physician');
			}
			$this->get_create($validation);
		}

		function get_edit($physician_id,$validation = null){
			$physician = Physician::where('physician_id','=',$physician_id)->where("facility_cd","=",User::current()->facility_cd)->first();
			if($physician == null)	return Redirect::to('Physician');
			$this->setNav();
			
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}

			$this->layout->content = View::make('physician.form',['nationalities' => $nationalities,'validation' => $validation,'physician'=>$physician]);
		}

		function post_edit($physician_id){
			$physician = Physician::where('physician_id','=',$physician_id)->where("facility_cd","=",User::current()->facility_cd)->first();
			if($physician == null)	return Redirect::to('Physician');
			$data = Input::all();
			$validation = Validator::make($data,[
					'fname' => 'required',
					'lname' => 'required',
					'bdate' => 'required',
					'gender' => 'required'
				]);
			if(!$validation->fails()){
				$user = User::current();
				$physician->fname = $data['fname'];
				$physician->mname = $data['mname'];
				$physician->lname = $data['lname'];
				$physician->name_suffix = $data['name_suffix'];
				$physician->civil_stat = $data['civil_stat'];
				$physician->bdate = $data['bdate'];
				$physician->gender = $data['gender'];
				$physician->nationality = $data['nationality'];
				$physician->no_st_blk = $data['no_st_blk'];
				$physician->regcode = $data['regcode'];
				$physician->provcode = $data['provcode'];
				$physician->citycode = $data['citycode'];
				$physician->bgycode = $data['bgycode'];
				$physician->tel_no = $data['tel_no'];
				$physician->mobile_no = $data['mobile_no'];
				$physician->email = $data['email'];
				$physician->updated_by = $user->user_id;
				$physician->updated_dt = date('Y-m-d H:i:s');
				$physician->save();
				return Redirect::to('Physician');
			}
			$this->get_edit($physician_id,$validation);
		}

		function get_apilist(){
			$user = User::current();
			$physicians = Physician::where('facility_cd','=',$user->facility_cd)->get();
			$result = [];
			foreach($physicians as $p => $physician){
				$physicians[$p]->gender = $physicians[$p]->gender == 'M' ? 'Male' : ($physicians[$p]->gender == 'F' ? 'Female' : '');
				$physicians[$p]->name = ucwords($physician->fname.' '.$physician->mname.' '.$physician->lname);
				$result[$physician->physician_id] = $physicians[$p];
			}
			return json_encode($result);
		}

	}