<?php
	class AddressDropdownController extends BaseController{

		function post_reglist(){
			$return = ['data' => []];

			$raw = Region::all();

			foreach($raw as $r){
				$return['data'][] = ['regcode' => $r['regcode'] , 'regname' => $r['regname']];
			}

			return json_encode($return);
		}

		function post_provlist(){
			$regcode = Input::get('regcode');
			$return = ['data' => []];
			$raw = [];
			if($regcode != null){
				$raw = Region::where('regcode','=',$regcode)->first()->provinces;
			}else{
				$raw = Province::all();
			}

			foreach($raw as $r){
				$return['data'][] = ['provcode' => $r['provcode'], 'provname' => $r['provname']];
			}
			return json_encode($return);
		}

		function post_citylist(){
			$provcode = Input::get('provcode');
			$return = ['data' => []];
			$raw = [];
			if($provcode != null){
				$raw = Province::where('provcode','=',$provcode)->first()->cities;
			}else{
				$raw = City::all();
			}

			foreach($raw as $r){
				$return['data'][] = ['citycode' => $r['citycode'], 'cityname' => $r['cityname']];
			}
			return json_encode($return);
		}

		function post_brgylist(){
			$citycode = Input::get('citycode');
			$return = ['data' => []];
			$raw = [];
			if($citycode != null){
				$raw = City::where('citycode','=',$citycode)->first()->barangays;
			}else{
				exit(json_encode([]));
			}
			foreach($raw as $r){
				$return['data'][] = ['bgycode' => $r['bgycode'], 'bgyname' => $r['bgyname']];
			}
			return json_encode($return);
		}
	}
?>