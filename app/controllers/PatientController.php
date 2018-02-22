<?php
	class PatientController extends BaseController{

		public $restful = true;
		public $layout = 'layout.default';

		function setNav($currentPage = null){
			$currentPage = 'Patient';
			if(User::guest()){
				$this->layout->nav = View::make('layout.nav_public');
			}else{
				$this->layout->nav = View::make('layout.nav_private',['currentPage' => $currentPage]);
			}
		}

		function get_clearfilter(){
			//Clear Session Filters
			Patient::clearListFilter();
			return Redirect::to('Patient');
		}

		function get_index(){
			$this->setNav();
			$user = User::current();
			/*for($i = 0; $i < 50; $i++){
				$p = new Patient();
				$p->patient_id = Patient::generateID();
				$p->seqno = Patient::generateSequenceNo();
				$p->facility_cd = $user->facility_cd;
				$p->save();
			}*/
			$patients = Patient::where('facility_cd','=',$user->facility_cd)
						->where('disable_flg','=','N')
						->where(function($t){
				$filter = Patient::getListFilter();
				if($filter != null){
					$t->orwhere('fname','like','%'.$filter.'%');
					$t->orwhere('mname','like','%'.$filter.'%');
					$t->orwhere('lname','like','%'.$filter.'%');

				}
				
			})->paginate(15);
			$this->layout->content = View::make('patient.list',[
					'patients' => $patients
				]);
		}

		


		function post_index(){
			$user = User::current();
			$patient_name = Input::get('patient_name');
			if($patient_name != null){
				Patient::setListFilter($patient_name);
				return Redirect::to('Patient');
			}else{
				$indexes = Input::get('index');
				foreach($indexes as $index){
					//Patient::where('patient_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->delete();
					$patient = Patient::where('patient_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->first();
					$patient->disable_flg = 'Y';
					$patient->save();
				}
				return Redirect::to('Patient');
				
			}
		}

		function get_create($validation = null){
			$this->setNav();
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}
			$this->layout->content = View::make('patient.form',['nationalities' => $nationalities,'validation' => $validation]);
		}


		function post_create(){
			$config = FacilityConfig::current();
			$data = Input::all();
			$validation = Validator::make($data,Patient::getRules(),Patient::getCustomMessages());
			if(!$validation->fails()){
				$user = User::current();
				unset($data['_token']);
				$data['patient_id'] = $config->auto_patient_id == 'N' ? $data['patient_id'] : Patient::generateID();
				$data['seqno'] = Patient::generateSequenceNo();
				$data['facility_cd'] = $user->facility_cd;
				$data['created_by'] = $user->user_id;
				$data['created_dt'] = date('Y-m-d H:i:s');
				Patient::insert($data);
				return Redirect::to('Patient');
			}
			$this->get_create($validation);
		}

		function get_edit($patient_id,$validation = null){
			$patient = Patient::where('patient_id','=',$patient_id)->where('facility_cd','=',User::current()->facility_cd)->first();
			if($patient == null)	return Redirect::to('Patient');
			$this->setNav();
			
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}

			$this->layout->content = View::make('patient.form',['nationalities' => $nationalities,'validation' => $validation,'patient'=>$patient]);
		}

		function post_edit($patient_id){
			$patient = Patient::where('patient_id','=',$patient_id)->where("facility_cd","=",User::current()->facility_cd)->first();
			if($patient == null)	return Redirect::to('Patient');
			$data = Input::all();
			$validation = Validator::make($data,[
					'fname' => 'required',
					'lname' => 'required',
					'bdate' => 'required',
					'gender' => 'required'
				]);
			if(!$validation->fails()){
				$user = User::current();
				$patient->fname = $data['fname'];
				$patient->mname = $data['mname'];
				$patient->lname = $data['lname'];
				$patient->name_suffix = $data['name_suffix'];
				$patient->civil_stat = $data['civil_stat'];
				$patient->bdate = $data['bdate'];
				$patient->gender = $data['gender'];
				$patient->nationality = $data['nationality'];
				$patient->no_st_blk = $data['no_st_blk'];
				$patient->regcode = $data['regcode'];
				$patient->provcode = $data['provcode'];
				$patient->citycode = $data['citycode'];
				$patient->bgycode = $data['bgycode'];
				$patient->tel_no = $data['tel_no'];
				$patient->mobile_no = $data['mobile_no'];
				$patient->email = $data['email'];
				$patient->updated_by = $user->user_id;
				$patient->updated_dt = date('Y-m-d H:i:s');
				$patient->save();
				return Redirect::to('Patient');
			}
			$this->get_edit($patient_id,$validation);
		}

		function get_apilist(){
			$user = User::current();
			$patients = Patient::where('facility_cd','=',$user->facility_cd)->get();
			$result = [];
			foreach($patients as $p => $patient){
				$patients[$p]->gender = $patients[$p]->gender == 'M' ? 'Male' : ($patients[$p]->gender == 'F' ? 'Female' : '');
				$patients[$p]->name = ucwords($patient->fname.' '.$patient->mname.' '.$patient->lname);
				$result[$patient->patient_id] = $patients[$p];
			}
			return json_encode($result);
		}

		

	}