<?php 

	class ReportController extends BaseController{
		public $layout = 'layout.default';

		/* do not forget to add this ---setup navigation--- */
		function setNav($currentPage = null){
			$currentPage = 'Reports';
			if(User::guest()){
				$this->layout->nav = View::make('layout.nav_public');
			}else{
				$this->layout->nav = View::make('layout.nav_private',['currentPage' => $currentPage]);
			}
		}
		/* do not forget to add this ---setup navigation--- */

		function get_index(){
			$this->setNav();	/* always add this for the nav to display */
			$this->layout->content = View::make('reports.census_summary');
		}/* end get_census */

	

		function dateFilter(){
			$date = Carbon\Carbon::now();
			$date = $date->subMonths(1);
			$month = str_pad($date->month,2,'0',STR_PAD_LEFT);
			return date($date->year.'-'.$month).'%';
		}

		static function dateFilterForHuman(){
			$date = Carbon\Carbon::now();
			$date = $date->subMonths(1);
			return date('F, Y',strtotime($date));
		}

		function dateIsBetween($from, $to, $date = 'now') {
		    $date = is_int($date) ? $date : strtotime($date); // convert non timestamps
		    $from = is_int($from) ? $from : strtotime($from); // ..
		    $to = is_int($to) ? $to : strtotime($to);         // ..
		    return ($date > $from) && ($date < $to); // extra parens for clarity
		}

		function get_report1($component_cd,$blood_type){
			$date_filter  = $this->dateFilter();
			$response = [
				'I' => ['D' => 0,'A' => 0, 'N' => 0, 'S' => 0, 'T' => 0],
				'O' => ['D' => 0,'A' => 0, 'N' => 0, 'S' => 0, 'T' => 0],
			];

			$units = BloodRequestDetails::with('bloodRequest')->self()
					->whereHas('bloodRequest',function($q) use ($blood_type){
						$q->where("blood_type",'LIKE',$blood_type."%");
					})
					->where(function($q){
						$q->orWhere('unit_stat','R');
						$q->orWhere('unit_stat','I');
					})
					->where('crossmatch_dt','like',$date_filter)
					->whereComponentCd($component_cd)
					->remember(120)
					->get();
			foreach($units as $unit){
				if(!$unit->bloodRequest){
					continue;
				}

				if($unit->unit_stat == 'R'){
					$this->unitCounter1($unit,$response);
				}else if($unit->unit_stat == 'I'){
					$this->unitCounter2($unit,$response);
				}

				
			}

			// echo "<pre>"; exit(print_r($response));
			return Response::json($response);
		}


		/*Counts appropriate units for category in the foreach loop in get_report1*/
		function unitCounter1(&$unit,&$response){
			$date = explode(' ',$unit->crossmatch_dt);
			$date = $date[0];
			$cdate = Carbon\carbon::createFromDate(date('Y',strtotime($date)) , date('m',strtotime($date)), date('d',strtotime($date)));
			$cdate = $cdate->addDays(1);
			$day_after_that_date = $cdate->year.'-'.str_pad($cdate->month,2,'0',STR_PAD_LEFT).'-'.str_pad($cdate->day,2,'0',STR_PAD_LEFT);
			
			$key = 'I';
			if($unit->bloodRequest->patient_care == 'I'){
				$key = 'I';
			}else if($unit->bloodRequest->patient_care == 'O'){
				$key = 'O';
			}else{
				continue;
			}
			if($this->dateIsBetween($date.' 06:00:00',$date.' 14:00:00',$unit->crossmatch_dt)){
				$response[$key]['D']++;
				$response[$key]['S']++;
			}else if($this->dateIsBetween($date.' 14:00:00',$date.' 22:00:00',$unit->crossmatch_dt)){
				$response[$key]['A']++;
				$response[$key]['S']++;
			}else if($this->dateIsBetween($date.' 22:00:00',$day_after_that_date.' 06:00:00',$unit->crossmatch_dt)){
				$response[$key]['N']++;
				$response[$key]['S']++;
			}
		}

		/*Counts appropriate units for category in the foreach loop in get_report1*/
		function unitCounter2(&$unit,&$response){
			$date = explode(' ',$unit->crossmatch_dt);
			$date = $date[0];
			$cdate = Carbon\carbon::createFromDate(date('Y',strtotime($date)) , date('m',strtotime($date)), date('d',strtotime($date)));
			$cdate = $cdate->addDays(1);
			$day_after_that_date = $cdate->year.'-'.str_pad($cdate->month,2,'0',STR_PAD_LEFT).'-'.str_pad($cdate->day,2,'0',STR_PAD_LEFT);
			
			$key = 'I';
			if($unit->bloodRequest->patient_care == 'I'){
				$key = 'I';
			}else if($unit->bloodRequest->patient_care == 'O'){
				$key = 'O';
			}else{
				continue;
			}
			$response[$key]['T']++;
			$response[$key]['S']++;
		}

		function get_report2(){

			$components = Component::whereDisableFlg('N')->lists('component_cd');
			$response = [];
			$date_filter  = $this->dateFilter();

			$response['I'] = [];
			foreach($components as $i => $cc){
				$response['I'][$cc] = 0;
			}

			$response['O'] = [];
			foreach($components as $i => $cc){
				$response['O'][$cc] = 0;
			}
			
			$requests = BloodRequest::self()
				->where('created_dt','like',$date_filter)
				->where('blood_type','like','%neg')
				->wherePatientCare('I')
				->get();

			foreach($requests as $request){
				foreach($request->details as $detail){
					$response['I'][$detail->component_cd] += 1;
				}
			}
			
			$requests2 = BloodRequest::self()
				->where('created_dt','like',$date_filter)
				->where('blood_type','like','%neg')
				->wherePatientCare('O')
				->get();

			foreach($requests2 as $request){
				foreach($request->details as $detail){
					$response['O'][$detail->component_cd] += 1;
				}
			}

			

			return Response::json($response);
		}
	
	}

?>
