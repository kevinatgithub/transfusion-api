<?php
	class BloodUnitController extends BaseController{

		public $restful = true;
		public $layout = 'layout.inline_fluid';

		function get_index($detail_id,$str_ids,$component_cd,$blood_type){
			$ids = explode(',', $str_ids);
			$donation_ids = [];
			foreach($ids as $id){
				$id = trim($id);
				if(strlen($id) > 0 && $id != "_"){
					$donation_ids[] = $id;
				}
			}
			

			$user = User::current();
			$component = Component::where('component_cd','=',$component_cd)->first();

			$unitsQuery = BloodUnit::where('blood_type','=',$blood_type)
								->where('component_cd','=',$component_cd)
								->where('comp_stat','=','AVA')
								->where('location','=',$user->facility_cd)
								->where(function($t){
									if(($donation_id = Session::get('BloodUnit_donation_id')) !== null){
										$t->where('donation_id','=',$donation_id);
									}
								});
			if(count($donation_ids) != 0){
				foreach ($donation_ids as $di) {
					$unitsQuery->where('donation_id','<>',$di);
				}
			}
			$units = $unitsQuery->orderBy('expiration_dt','ASC')->paginate(15);

			$this->layout->content = View::make('bloodUnit.list',[
					'detail_id' => $detail_id,'str_ids' => $str_ids, 'component' => $component,	'blood_type' => $blood_type, 'units' => $units
				]);
		}

		function post_index($detail_id,$donation_ids,$component_cd,$blood_type){
			$donation_id = Input::get('donation_id');
			Session::put('BloodUnit_donation_id',$donation_id);
			return Redirect::to('BloodUnit/'.$detail_id.'/'.$donation_ids.'/'.$component_cd.'/'.$blood_type);
		}

		function get_clearFilter($i,$d,$c,$b){
			Session::pull('BloodUnit_donation_id');
			return Redirect::to('BloodUnit/'.$i.'/'.$d.'/'.$c.'/'.$b);
		}

		function otherSource($detail_id,$str_ids,$component_cd,$blood_type,$validation = null){
			$user = User::current();
			$component = Component::where('component_cd','=',$component_cd)->first();
			$sources = OtherSource::where('user_facility_cd','=',$user->facility_cd)->get();
			$sources_js = [];
			$sources_items = [];
			foreach($sources as $source){
				$sources_js[$source->source_id] = $source;
				$sources_items[$source->source_id] = $source->facility_name;
			}
			$sources_items[''] = '-- New Source --';
			$types = DB::table('r_codedtl')->where('code','=','FACILITY_TYPE')->where('disable_flg','=','N')->get(['codedtl_cd','code_val']);
			$types_items = ['' => 'Please Select'];
			foreach($types as $type){
				$types_items[$type->codedtl_cd] = $type->code_val;
			}
			$categories = DB::table('r_codedtl')->where('code','=','FACILITY_CAT')->where('disable_flg','=','N')->get(['codedtl_cd','code_val']);
			$categories_items = ['' => 'Please Select'];
			foreach($categories as $category){
				$categories_items[$category->codedtl_cd] = $category->code_val;
			}
			$this->layout->content = View::make('bloodUnit.otherSource',[
				'detail_id' => $detail_id,'str_ids' => $str_ids, 'component' => $component, 'blood_type' => $blood_type,	'sources' => $sources , 'sources_js' => $sources_js , 'sources_items' => $sources_items,
				'types' => $types, 'types_items' => $types_items, 'categories' => $categories , 'categories_items' => $categories_items , 'validation' => $validation
			]);
		}

		function get_otherSource($detail_id,$str_ids,$component_cd,$blood_type){
			$this->otherSource($detail_id,$str_ids,$component_cd,$blood_type);
		}

		function post_otherSource($detail_id,$str_ids,$component_cd,$blood_type){
			$user = User::current();
			$data = Input::all();
			$validation = Validator::make($data,[
				'donation_id' => 'required|unique:donation',
				'source_serial_no' => 'required',
				'collected_dt' => 'required',
				'expiration_dt' => 'required',
				'component_vol' => 'required|numeric',
				'facility_name' => 'required'
			]);
			if(!$validation->fails()){
				if($data['source_id'] == ''){
					$source = new OtherSource();
					$source->seqno = OtherSource::generateSequenceNo();
					$source->source_id = OtherSource::generateID();
					$source->created_by = $user->user_id;
					$source->created_dt = date('Y-m-d H:i:s');
				}else{
					$source = OtherSource::where('source_id','=',$data['source_id'])->where('user_facility_cd','=',$user->facility_cd)->first();
					$source->updated_by = $user->user_id;
					$source->updated_dt = date('Y-m-d H:i:s');
				}
				$source->user_facility_cd = $user->facility_cd;
				$source->facility_name = $data['facility_name'];
				$source->type = $data['type'];
				$source->category = $data['category'];
				$source->no_st_blk = $data['no_st_blk'];
				$source->bgycode = $data['bgycode'];
				$source->citycode = $data['citycode'];
				$source->provcode = $data['provcode'];
				$source->regcode = $data['regcode'];
				$source->zipcode = $data['zipcode'];
				$source->tel_no = $data['tel_no'];
				$source->mobile_no = $data['mobile_no'];
				$source->fax_no = $data['fax_no'];
				$source->email = $data['email'];
				$source->contact_person = $data['contact_person'];
				$source->designation = $data['designation'];
				$source->save();
				
				$detail = BloodRequestDetails::where('id','=',$detail_id)->first();
				$detail->source_id = $source->source_id;
				$detail->donation_id = $data['donation_id'];
				$detail->source_serial_no = $data['source_serial_no'];
				$detail->collected_dt = $data['collected_dt'];
				$detail->expiration_dt = $data['expiration_dt'];
				$detail->component_vol = $data['component_vol'];
				if($detail->unit_stat == 'L' || $detail->unit_stat == ''){
					$detail->unit_stat = 'C';

				}
				/*$detail->save();*/

				return "
				<script type='text/javascript'>
				parent.selectUnitFromOtherSource(".json_encode($detail).");
				</script>
				";
			}
			$this->otherSource($detail_id,$str_ids,$component_cd,$blood_type,$validation);
		}

		function post_viewSave(){
			$data = Input::all();
			
			$bloodRequest = $data['bloodRequest'];
			$details = $data['details'];
			$user_id = $data['user_id'];
			$facility_cd = $data['facility_cd'];

			foreach($details as $detail){
				if(array_key_exists('forRemoval', $detail)){
					$d = BloodRequestDetails::where('id','=',$detail['id'])->where('request_id','=',$bloodRequest['request_id'])->first();
					//$d->delete();
					$d->disable_flg = 'Y';
					$d->save();
				}else if(array_key_exists('newDetail', $detail)){
					BloodRequestDetails::insert([
						'id' => BloodRequestDetails::generateID(0,$bloodRequest['request_id']),
						'facility_cd' => $facility_cd,
						'request_id' => $bloodRequest['request_id'],
						'component_cd' => $detail['component_cd'],
						'unit_stat' => 'L'
					]);
				}

			}
			exit(json_encode($data));
		}

		function post_lookupSave(){
			$data = Input::all();
			
			$bloodRequest = $data['bloodRequest'];
			$details = $data['details'];
			$user_id = $data['user_id'];
			$facility_cd = $data['facility_cd'];

			foreach($details as $detail){
				$d = BloodRequestDetails::where('id','=',$detail['id'])->where('request_id','=',$bloodRequest['request_id'])->first();
				$d->component_vol = $detail['component_vol'];
				$d->donation_id = $detail['donation_id'];
				$d->collected_dt = $detail['collected_dt'];
				$d->source_serial_no = $detail['source_serial_no'];
				$d->expiration_dt = $detail['expiration_dt'];
				$d->source_id = $detail['source_id'];
				if($d->crossmatch_result == '' && $detail['expiration_dt'] != ''){
					$d->unit_stat = 'C';
				}
				$d->save();
				if($detail['source_id'] == ''){
					DB::update(DB::raw("UPDATE component SET comp_stat = 'RES' WHERE donation_id = '".$detail['donation_id']."' AND component_cd = '".$detail['component_cd']."'"));
				}else{
					//exit(json_encode(true));
					$unit = BloodUnit::where("donation_id","=",$detail["donation_id"])->where("component_cd","=",$detail["component_cd"])->first();
					if($unit == null){
						BloodUnit::insert([
							'donation_id' => $detail['donation_id'],
							'component_cd' => $detail['component_cd'],
							'blood_type' => $bloodRequest['blood_type'],
							'location' => User::current()->facility_cd,
							'expiration_dt' => $detail['expiration_dt'],
							'component_vol' => $detail['component_vol'],
							'comp_stat' => 'RES',
							'created_by' => User::current()->user_id,
							'created_dt' => date('Y-m-d H:i:s')
						]);
						
					}
				}
			}
			exit(json_encode($data));
		}

		function post_crossmatchSave(){
			$data = Input::all();
			
			/*$verifier = $data['verifier'];
			if(checkVerifier($verifier) == false){
				exit(json_encode(false));
			}*/
			$bloodRequest = $data['bloodRequest'];
			$details = $data['details'];
			$user_id = $data['user_id'];
			$facility_cd = $data['facility_cd'];

			foreach($details as $detail){
				$d = BloodRequestDetails::where('id','=',$detail['id'])/*->where('request_id','=',$bloodRequest['request_id'])*/->first();
				//$d = BloodRequestDetails::where('dona')
				if($d->component_cd == '30' || $d->component_cd == '40' || $d->component_cd == '50' || $d->component_cd == '60'){
					$d->crossmatch_result = 'T';
				}else{
					$d->crossmatch_result = $detail['crossmatch_result'];
				}
				$d->crossmatch_by = $user_id;
				$d->crossmatch_dt = date('Y-m-d H:i:s');
				if($d->component_cd == '30' || $d->component_cd == '40' || $d->component_cd == '50' || $d->component_cd == '60'){
					$d->unit_stat = 'R';
				}else if($d->unit_stat == 'C' || $d->unit_stat == 'M' || $d->unit_stat == ''){
					if(strtoupper($detail['crossmatch_result']) == 'C' || strtoupper($detail['crossmatch_result']) == 'T'){
						$d->unit_stat = 'R';
					}elseif(strtoupper($detail['crossmatch_result']) == 'I'){
						$d->unit_stat = 'M';
					}
				}
				$d->save();
			}
			exit(json_encode($data));
		}

		function post_returnUnit(){
			$detail = Input::get('detail');
			$action = Input::get('action');
			$discard_reason = Input::get('discard_reason');
			$remark = Input::get('remark');

			$user = User::current();

			$d = BloodRequestDetails::where('id','=',$detail['id'])->where('request_id','=',$detail['request_id'])->first();
			if($action == 'R'){
				$d->unit_stat = 'X';
				$d->return_reason = $detail['return_reason'];
				$d->save();
				DB::update(DB::raw("UPDATE component SET comp_stat = 'AVA' WHERE donation_id = '".$detail['donation_id']."' AND component_cd = '".$detail['component_cd']."'"));
			}else if($action == 'D'){
				$d->unit_stat = 'D';
				$d->save();
				DB::update(DB::raw("UPDATE component SET comp_stat = 'DIS' WHERE donation_id = '".$detail['donation_id']."' AND component_cd = '".$detail['component_cd']."'"));
				DB::insert(DB::raw("INSERT blood_discard VALUES(
						'".$user->facility_cd."',
						Now(),
						'".$user->user_id."',
						null,
						'".$detail['donation_id']."',
						'".$detail['component_cd']."',
						'$discard_reason',
						'$remark'
					)"));
			}

			exit(json_encode(Input::all()));
		}

		function post_verifyUnitForIssuance(){
			$detail = Input::get('detail');
			$bloodRequest = Input::get('bloodRequest');
			$user = User::current();
			
			$d = BloodRequestDetails::where('request_id','=',$bloodRequest['request_id'])
			->where('facility_cd','=',$user->facility_cd)
			->where('donation_id','=',$detail['donation_id'])
			->where('component_cd','=',$detail['component_cd'])
			->where(function($query){
				$query->orWhere('crossmatch_result','=','C');
				$query->orWhere('crossmatch_result','=','T');
			})
			->where('unit_stat','=','R')->first();
			

			if($d == null){
				exit(json_encode(['status' => false , 'component_cd' => $detail['component_cd']]));
			}else{
				exit(json_encode(['status' => true]));
			}
		}

		function post_issueBloodUnits(){
			$forIssuance = Input::get('forIssuance');
			$bloodRequest = Input::get('bloodRequest');
			$verifier = Input::get('verifier');
			if(checkVerifier($verifier) == false){
				exit(json_encode(false));
			}
			$user = User::current();

			$request = BloodRequest::where('seqno','=',$bloodRequest['seqno'])->first();
			$request->status = 'I';
			$request->crossmatch_verified_by = $verifier['user_id'];
			$request->save();

			//exit(json_encode($forIssuance));
			foreach($forIssuance as $d){
				if($d['status'] == true){
					DB::update(DB::raw("UPDATE component SET comp_stat = 'ISS' WHERE donation_id = '".$d['donation_id']."' AND component_cd = '".$d['component_cd']."'"));
					DB::update(DB::raw("UPDATE bts_blood_request_dtls 
						SET unit_stat = 'I' 
						WHERE facility_cd = '".$user->facility_cd."' AND donation_id = '".$d['donation_id']."' AND component_cd = '".$d['component_cd']."'"));
				}
			}

			exit(json_encode(Input::all()));
		}

		function post_detailReactions(){
			$detail = Input::get('detail');
			$reactions = [];

			$detailReactions = DetailReaction::where('request_dtl_id','=',$detail['id'])->get();
			foreach($detailReactions as $reaction){
				$reactions[$reaction->reaction_id] = true;
			}

			exit(json_encode(['reactions' => $reactions]));
		}

		function post_saveDetailReactions(){
			//exit(json_encode(Input::all()));
			$detail = Input::get('detail');
			$reactions = $detail['reactions'];
			$db_detail = BloodRequestDetails::where('id','=',$detail['id'])->first();

			$db_detail->tfsn_start_by = $detail['tfsn_start_by'];
			$db_detail->tfsn_start_dt = $detail['tfsn_start_dt'];
			$db_detail->tfsn_end_by = $detail['tfsn_end_by'];
			$db_detail->tfsn_stat = $detail['tfsn_stat'];
			$db_detail->tfsn_end_dt = $detail['tfsn_end_dt'];
			$db_detail->tfsn_set_remove_by = $detail['tfsn_set_remove_by'];
			$db_detail->tfsn_set_remove_dt = $detail['tfsn_set_remove_dt'];
			$db_detail->tfsn_end_bp = $detail['tfsn_end_bp'];
			$db_detail->tfsn_end_pr = $detail['tfsn_end_pr'];
			$db_detail->tfsn_end_rr = $detail['tfsn_end_rr'];
			$db_detail->tfsn_end_temp = $detail['tfsn_end_temp'];

			$db_detail->save();
			
			foreach($reactions as $reaction_id => $reaction){
				if($reaction_id == '0'){
					continue;
				}
				if($reaction){
					$check = DetailReaction::where('request_dtl_id','=',$detail['id'])->where('reaction_id','=',$reaction_id)->get();
					if(count($check) == 0){
						DetailReaction::insert([
							'request_dtl_id' => $detail['id'],
							'reaction_id' => $reaction_id
						]);
					}
				}else if(!$reaction){
					DetailReaction::where('request_dtl_id','=',$detail['id'])->where('reaction_id','=',$reaction_id)->delete();
				}
			}

			exit(json_encode(Input::all()));
		}

	}