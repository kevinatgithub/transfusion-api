<?php
	class BloodRequestPhysicianController extends BaseController{
		public $layout = 'layout.inline';
		public $restful = true;

		function get_clearfilter(){
			//Clear Session Filters
			Physician::clearListFilter();
			return Redirect::to('BloodRequestPhysician/list');
		}

		function get_index(){
			$this->get_list();
		}

		function get_list(){
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
			$content = View::make('physician.list',[
					'physicians' => $physicians,
					'inline' => true
				]);
			$js_physicians = [];
			foreach($physicians as $i => $physician){
				$js_physicians[$physician->physician_id] = $physician;
			}

			$content->list_script = View::make('bloodRequest.bloodrequestphysician',['js_physicians' => $js_physicians]);
			$this->layout->content = $content;
		}

		function post_list(){
			$user = User::current();
			$physician_name = Input::get('physician_name');
			if($physician_name != null){
				Physician::setListFilter($physician_name);
				return Redirect::to('BloodRequestPhysician/list');
			}else{
				$indexes = Input::get('index');
				foreach($indexes as $index){
					//Physician::where('physician_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->delete();
					$physician = Physician::where('physician_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->first();
					$physician->disable_flg = 'Y';
					$physician->save();
				}
				return Redirect::to('BloodRequestPhysician/list');
				
			}
		}


		function get_create($validation = null){
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}
			$content = View::make('physician.form',['nationalities' => $nationalities,'validation' => $validation,'inline' => true]);
			$content->script = View::make('bloodRequest.bloodrequestphysician');
			$this->layout->content = $content;
		}


		function post_create(){
			$data = Input::all();
			$validation = Validator::make($data,[
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
				$physician = $data;

				exit("
					<script type='text/javascript'>
						physician = ".json_encode($physician).";
						parent.get('physician' , physician);
						parent.get('formVisible',true);
						parent.get('physicianFrameVisible',false);
						attending_physician_name = physician.fname + ' ' + physician.mname + ' ' + physician.lname;
						parent.get('attending_physician',attending_physician_name);
						parent.reloadPhysicianFrame();
					</script>
					");
			}else{
				$this->get_create($validation);
			}
		}


		function get_edit($physician_id,$validation = null){
			$physician = Physician::where('physician_id','=',$physician_id)->where('facility_cd','=',User::current()->facility_cd)->first();
			if($physician == null)	return Redirect::to('BloodRequestPhysician');
			
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}

			$content = View::make('physician.form',['nationalities' => $nationalities,'validation' => $validation,'physician'=>$physician,'inline' => true]);
			$content->script = View::make('bloodRequest.bloodrequestphysician');
			$this->layout->content = $content;
		}

		function post_edit($physician_id){
			$physician = Physician::where('physician_id','=',$physician_id)->where('facility_cd','=',User::current()->facility_cd)->first();
			if($physician == null)	return Redirect::to('BloodRequestPhysician');
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
				exit("
					<script type='text/javascript'>
						physician = ".json_encode($physician).";
						parent.get('physician' , physician);
						parent.get('formVisible',true);
						parent.get('physicianFrameVisible',false);
						attending_physician_name = physician.fname + ' ' + physician.mname + ' ' + physician.lname;
						parent.get('attending_physician',attending_physician_name);
						parent.reloadPhysicianFrame();
					</script>
					");
			}
			$this->get_edit($physician_id,$validation);
		}
	}