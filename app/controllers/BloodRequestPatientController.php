<?php
	class BloodRequestPatientController extends BaseController{
		public $layout = 'layout.inline';
		public $restful = true;

		function get_clearfilter(){
			//Clear Session Filters
			Patient::clearListFilter();
			return Redirect::to('BloodRequestPatient/list');
		}

		function get_index(){
			$this->get_list();
		}

		function get_list(){
			$user = User::current();
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
			$content = View::make('patient.list',[
					'patients' => $patients,
					'inline' => true
				]);
			$js_patients = [];
			foreach($patients as $i => $patient){
				$js_patients[$patient->patient_id] = $patient;
			}

			$content->list_script = View::make('bloodRequest.bloodrequestpatient',['js_patients' => $js_patients]);
			$this->layout->content = $content;
		}

		function post_list(){
			$user = User::current();
			$patient_name = Input::get('patient_name');
			if($patient_name != null){
				Patient::setListFilter($patient_name);
				return Redirect::to('BloodRequestPatient/list');
			}else{
				$indexes = Input::get('index');
				foreach($indexes as $index){
					//Patient::where('patient_id','=',$index)->delete();
					$patient = Patient::where('patient_id','=',$index)->where("facility_cd","=",User::current()->facility_cd)->first();
					$patient->disable_flg = 'Y';
					$patient->save();
				}
				return Redirect::to('BloodRequestPatient/list');
				
			}
		}


		function get_create($validation = null){
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}
			$content = View::make('patient.form',['nationalities' => $nationalities,'validation' => $validation,'inline' => true]);
			$content->script = View::make('bloodRequest.bloodrequestpatient');
			$this->layout->content = $content;
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
				$patient = $data;
				exit("
					<script type='text/javascript'>
						patient = ".json_encode($patient).";
						parent.get('patient' , patient);
						parent.get('formVisible',true);
						parent.get('patientFrameVisible',false);
						parent.reloadPatientFrame();
					</script>
					");
			}else{
				$this->get_create($validation);
			}
		}


		function get_edit($patient_id,$validation = null){
			$patient = Patient::where('patient_id','=',$patient_id)->where('facility_cd','=',User::current()->facility_cd)->first();
			if($patient == null)	return Redirect::to('BloodRequestPatient');
			
			$r_nationalities = DB::table('rcountry')->get(['countrycode','nationality']);
			$nationalities = [];
			foreach($r_nationalities as $nationality){
				$nationalities[$nationality->countrycode] = $nationality->nationality;
			}

			$content = View::make('patient.form',['nationalities' => $nationalities,'validation' => $validation,'patient'=>$patient,'inline' => true]);
			$content->script = View::make('bloodRequest.bloodrequestpatient');
			$this->layout->content = $content;
		}

		function post_edit($patient_id){
			$patient = Patient::where('patient_id','=',$patient_id)->where('facility_cd','=',User::current()->facility_cd)->first();
			if($patient == null)	return Redirect::to('BloodRequestPatient');
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
				exit("
					<script type='text/javascript'>
						patient = ".json_encode($patient).";
						parent.get('patient' , patient);
						parent.get('formVisible',true);
						parent.get('patientFrameVisible',false);
						parent.reloadPatientFrame();
					</script>
					");
			}
			$this->get_edit($patient_id,$validation);
		}
	}