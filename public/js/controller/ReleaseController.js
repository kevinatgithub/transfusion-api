function ReleaseController($scope,$http){


	$scope.release.action = 'R';
	$scope.release.other_reason_discard = false;
	$scope.release.other_reason_discard_text = '';
	$scope.release.issuanceInitiated = false;
	$scope.release.forIssuance = [];
	for(i in $scope.release.details){
		d = $scope.release.details[i];
		if(d.unit_stat == 'R' && (d.crossmatch_result == 'C' || d.crossmatch_result == 'T')){
			$scope.release.forIssuance[$scope.release.forIssuance.length] = {
				source_type : 1,
				status : null,
				error : null
			};
		}
	}

	$scope.release.setCurrent = function(detail){
		console.log(detail);
		$scope.release.current = detail;
	}

	$scope.release.returnUnit = function(){
		if($scope.release.action == 'R'){
			if($scope.release.current.return_reason == undefined || $scope.release.current.return_reason == null){
				alert("Please provide reason for return of blood unit");
				return;
			}
		}else if($scope.release.action == 'D'){
			if($scope.release.other_reason_discard == ''){
				alert("Please select reason for discard of blood unit");
				return;
			}else if($scope.release.other_reason_discard == 'OTH' && $scope.release.other_reason_discard_text == ''){
				alert("Please provide a description for the reason to discard");
				return;
			}
		}
		$("#cancelDetailForm").modal('hide');
		$scope.release.current.processing = true;
		var formData = {
			detail : $scope.release.current,
			action : $scope.release.action,
			discard_reason : $scope.release.other_reason_discard,
			remark : $scope.release.other_reason_discard_text
		};
		$http.post(url_returnUnit,formData).success(function(data){
			console.log(data);
			if(data.action == 'R'){
				$scope.release.current.unit_stat = 'X';
				$scope.updateDetailStatusGlobal($scope.release.current);
			}else if(data.action == 'D'){
				$scope.release.current.unit_stat = 'D';
				$scope.updateDetailStatusGlobal($scope.release.current);
			}
			$scope.release.current.processing = false;
		});
	}

	$scope.release.reserveRequest = function(submit){
		if(submit == true){
			$scope.release.reserving = true;
			var formData = {
				bloodRequest : $scope.bloodRequest
			};
			$http.post(url_reserveRequest,formData).success(function(data){
				console.log(data);
				window.location.reload();
			});
		}
		var count = 0, count2 = 0;
		for(i in $scope.release.details){
			d = $scope.release.details[i];
			if((d.crossmatch_result == 'C' || d.crossmatch_result == 'T') && d.unit_stat == 'R'){
				count++;
			}else if(d.unit_stat == 'M'){
				count2++;
			}
		}
		if(count == 0){
			alert('No Blood Unit to Release');
			return;
		}

		if(count2 > 0){
			alert("Please Return/Discard Blood Units that are mismatched");
			return;
		}

		$("#reserveRequestDetails").modal('show');
	}

	$scope.release.openIssuanceForm = function(){
		$scope.release.issuanceInitiated = true;
	}

	$scope.release.closeIssuanceForm = function(){
		$scope.release.issuanceInitiated = false;	
	}

	$scope.release.verifyUnitForIssuance = function(detail){
		if(detail.component_cd == ''){
			detail.error = "Please select component";
			return;
		}
		if(detail.donation_id == ''){
			detail.error = "Please enter Donation ID";
			return;
		}
		if($scope.release.verifyCheckDuplicate(detail)){
			detail.status = 'loading';
			var formData = {
				detail : detail,
				bloodRequest : $scope.bloodRequest
			};
			$http.post(url_verifyUnitForIssuance,formData).success(function(data){
				console.log(data);
				detail.status = data.status;
				if(detail.status == false){
					detail.error = "Blood Unit not Valid";
				}
			});
		}
	}

	$scope.release.verifyCheckDuplicate = function(detail){
		var count = 0;
		for(i in $scope.release.forIssuance){
			d = $scope.release.forIssuance[i];
			if(detail.donation_id != null){
				if(d.donation_id == detail.donation_id && d.component_cd == detail.component_cd && d.source_type == detail.source_type){
					count++;
				}
			}
		}
		console.log(count);
		if(count > 1){
			detail.status = false;
			detail.error = "Duplicate Donation ID";
			return false;
		}

		detail.status = true;
		return true;
		
	}

	$scope.release.confirmIssuance = function(){
		$("#verifierForm").modal('show');
		$scope.verifier.setVerify($scope.release.issueBloodUnits);
	}

	$scope.release.issueBloodUnits = function(){
		if($scope.verifier.user_id == null || $scope.verifier.password == null){
			return false;
		}

		if($scope.verifier.user_id == '' || $scope.verifier.password == ''){
			return false;
		}

		ready = 0;

		for(i in $scope.release.forIssuance){
			d = $scope.release.forIssuance[i];
			if(d.status == true){
				ready++;
			}
		}

		if(ready == 0){
			alert("There are no Blood Units ready to release.");
			return false;
		}


		var formData = {
			forIssuance : $scope.release.forIssuance,
			bloodRequest : $scope.bloodRequest,
			verifier : $scope.verifier
		};

		$http.post(url_issueBloodUnits,formData).success(function(data){
			console.log(data);
			if(data == 'false'){
				alert("Verifier Authentication Failed! Please check User ID and Password!");
				return false;
			}
			$scope.release.openIssuanceReciept();
			window.location.reload();
		});
	}

	$scope.release.openIssuanceReciept = function(){
		return false;
		window.open(url_issuanceForm+"/"+$scope.bloodRequest.seqno,"Print Blood Issuance Form","status=0,location=0,resizable=0,width=1000, height=800");
	}
}