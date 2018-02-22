function BloodRequestController($scope,$http){

			$scope.bloodRequest = bloodRequest;

			/*Verifier*/
			$scope.verifier = {
				user_id : null,
				password : null,
				verify : null
			};

			$scope.verifier.setVerify = function(callback){
				$scope.verifier.verify = callback;
			}
			
			/*Details*/
			$scope.view = {
				details : getDetails(),
				form : true,
				editMode : false,
				newDetail_component_cd : '',
				newDetailCount : 0
			};

			$scope.view.toggleEditMode = function(){
				if($scope.view.editMode){
					/*for(i in $scope.view.details){
						if($scope.view.details[i].newDetail){
							$scope.view.details[i] = undefined;
						}
					}*/
					$scope.view.details = getDetails();
					$scope.view.editMode = false;
				}else{
					$scope.view.editMode = true;
				}
			}

			$scope.view.printSticker = function(detail_id,type){
				window.open(url_printSticker+detail_id+"/"+type,"Print Sticker",'status=0,location=0,resizable=0,width=180, height=50');
			}

			$scope.view.addNewDetail = function(){
				if($scope.view.newDetail_component_cd == ''){
					alert('Please select component');
					return;
				}
				$scope.view.details[$scope.view.newDetailCount] = {
					component_cd : $scope.view.newDetail_component_cd,
					component : {
						comp_name : components[$scope.view.newDetail_component_cd]
					},
					newDetail : true
				};

				$scope.view.newDetailCount++;

				$scope.view.newDetail_component_cd = '';
			}

			$scope.view.checkChanges = function(){
				var forRemovals = 0, newDetails = 0;
				for(i in $scope.view.details){
					d = $scope.view.details[i];
					if(d.forRemoval){
						forRemovals++;
					}
					if(d.newDetail){
						newDetails++;
					}
				}
				if(forRemovals == 0 && newDetails == 0){
					return false;
				}
				return true;
			}

			$scope.view.removeDetail = function(detail){
				detail.forRemoval = true;
			}

			$scope.view.cancelChanges = function(){
				if(!$scope.view.checkChanges()){
					$scope.view.editMode = false;
				}else{
					window.location.reload();
				}
			}

			$scope.view.saveChanges = function(detail){
				if(!$scope.view.checkChanges()){
					$scope.view.editMode = false;
				}else{
					var formData = {
						details : $scope.view.details,
						bloodRequest : bloodRequest,
						user_id : user_id,
						facility_cd : facility_cd
					};
					$http.post(url_viewSave,formData).success(function(data){
						console.log(data);
						window.location.reload();
					});
				}
			}
			
			/*Lookup*/
			$scope.lookup = {
				editMode : false,
				frame : false,
				form : false,
				currentDetail : null,
				temporaryDetails : [],
				frame_src : '',
				details : getDetails(),
				openBloodUnitFrame : function(detail){
					ids = ['_'];
					for(i in $scope.lookup.details){
						d = $scope.lookup.details[i];
						if(typeof d == 'object'){
							id = d.donation_id;
							if(id != null){
								if(id.length > 0){
									ids[ids.length] = id;
								}
							}
						}
					}
					$scope.lookup.currentDetail = detail;
					$scope.lookup.frame_src = getLookUpFrameURL(detail.id,ids,detail.component_cd);
					$scope.changeView(5);
				}
			};

			

			/*Cross-match*/
			$scope.crossmatch = {
				details : getDetails(),
				form : false,
				editMode : false
			};

			/*Return/Release*/
			$scope.release = {
				details : getDetails(),
				form : false,
				current : null
			};

			/*Investigate*/
			$scope.investigate = {
				form : false,
				details : getDetails(),
				editMode : false,
				loading : false,
				current : {
					crossmatch_result : null
				}
			};

			/*Cancel*/
			$scope.cancel = {
				form : false,
				details : getDetails(),
				editMode : true
			};

			
			
			$scope.changeView = function (v){
				switch(v){
					case 0: 	//Details
						$scope.view.form = true;		$scope.lookup.form = false;		$scope.crossmatch.form = false;
						$scope.release.form = false;	$scope.cancel.form = false;		$scope.lookup.frame = false;
						$scope.investigate.form = false;
					break;
					case 1: 	//Lookup
						$scope.view.form = false;	$scope.lookup.form = true;		$scope.crossmatch.form = false;
						$scope.release.form = false;	$scope.cancel.form = false;		$scope.lookup.frame = false;
						$scope.investigate.form = false;
					break;
					case 2: 	//Cross-match
						$scope.view.form = false;	$scope.lookup.form = false;		$scope.crossmatch.form = true;
						$scope.release.form = false;	$scope.cancel.form = false;		$scope.lookup.frame = false;
						$scope.investigate.form = false;
					break;
					case 3: 	//Return/Release
						$scope.view.form = false;	$scope.lookup.form = false;		$scope.crossmatch.form = false;
						$scope.release.form = true;		$scope.cancel.form = false;		$scope.lookup.frame = false;
						$scope.investigate.form = false;
					break;
					case 4: 	//Cancel
						$scope.view.form = false;	$scope.lookup.form = false;		$scope.crossmatch.form = false;
						$scope.release.form = false;	$scope.cancel.form = true;		$scope.lookup.frame = false;
						$scope.investigate.form = false;
					break;
					case 5: 	//Lookup Frame
						$scope.view.form = false;	$scope.lookup.form = false;		$scope.crossmatch.form = false;
						$scope.release.form = false;	$scope.cancel.form = false;		$scope.lookup.frame = true;
						$scope.investigate.form = false;
					break;
					case 6:
						$scope.view.form = false;	$scope.lookup.form = false;		$scope.crossmatch.form = false;
						$scope.release.form = false;	$scope.cancel.form = false;		$scope.lookup.frame = false;
						$scope.investigate.form = true;
					break;
				}
			};

			window.get = function(name){
				return $scope[name];
			}
			

			window.set = function(name,data){
				 $scope.$apply(function(){
		            $scope[name] = data;
		         });
			}

			window.getDetails = function(){
				return $scope.view.details;
			};

			$scope.updateDetailStatusGlobal = function(detail){
				$scope.view.details[detail.id].unit_stat = detail.unit_stat;
				$scope.cancel.details[detail.id].unit_stat = detail.unit_stat;
			}

			
		}