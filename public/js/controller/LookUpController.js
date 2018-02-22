function LookUpController($scope,$http){
			

			window.selectUnit = function(unit,donation){
				$scope.$apply(function(){
					$scope.lookup.temporaryDetails[$scope.lookup.temporaryDetails.length] = $scope.lookup.currentDetail;
		            $scope.lookup.currentDetail.donation_id = unit.donation_id;
		            $scope.lookup.currentDetail.component_vol = unit.component_vol;
		            $scope.lookup.currentDetail.expiration_dt = unit.expiration_dt;
		            $scope.lookup.currentDetail.blood_unit = unit;
		            $scope.lookup.currentDetail.donation = unit.donation;
		            prepareDetail($scope.lookup.currentDetail);
		            $scope.changeView(1);
					$scope.lookup.frame_src = url_blank;
		         });
			}

			window.selectUnitFromOtherSource = function(detail){
				$scope.$apply(function(){
					$scope.lookup.temporaryDetails[$scope.lookup.temporaryDetails.length] = $scope.lookup.currentDetail;
					$scope.lookup.currentDetail.donation_id = detail.donation_id;
					$scope.lookup.currentDetail.source_serial_no = detail.source_serial_no;
					$scope.lookup.currentDetail.collected_dt = detail.collected_dt;
					$scope.lookup.currentDetail.source_id = detail.source_id;
					$scope.lookup.currentDetail.expiration_dt = detail.expiration_dt;
					$scope.lookup.currentDetail.component_vol = detail.component_vol;
					prepareDetail($scope.lookup.currentDetail);
					$scope.changeView(1);
					$scope.lookup.frame_src = url_blank;
				});
			}

			window.cancelLookUp = function(){
				$scope.$apply(function(){
					$scope.changeView(1);
					$scope.lookup.frame_src = url_blank;
				});
			}

			$scope.lookup.saveChanges = function(){
				var formData = {
					details : $scope.lookup.details,
					bloodRequest : bloodRequest,
					user_id : user_id,
					facility_cd : facility_cd
				};
				$http.post(url_lookUpSave,formData).success(function(data){
					console.log(data);
					//$scope.lookup.editMode = false;
					window.location.reload();
				});
				
			};

			$scope.lookup.toggleEditMode = function(){
				if($scope.lookup.editMode){
					for(i in $scope.lookup.temporaryDetails){
						d = $scope.lookup.temporaryDetails[i];
						d.donation_id = null;
						d.blood_unit = null;
						d.donation = null;
						d.source_id = null;
						d.expiration_dt = null;
						d.component_vol = null;
					}
					$scope.lookup.editMode = false;
				}else{
					$scope.lookup.editMode = true;
				}
			}
		}