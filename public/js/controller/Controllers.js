function BloodRequestController($scope,Patient){
	$scope.formVisible = true;
	$scope.patientListVisible = true;
	$scope.patients = Patient.query();
	//$scope.patients = [];

	$scope.selectedPatient = null;
	
	$scope.selectPatient = function(patient_id){
		for(var p in $scope.patients){
			patient = $scope.patients[p];
			if(patient.patient_id == patient_id){
				$scope.selectedPatient = patient;
			}
		}
		$scope.formVisible =true;
		$scope.patientListVisible = false;
	}

	$scope.showPatientList = function(){
		$scope.formVisible =false;
		$scope.patientListVisible = true;
	}
}