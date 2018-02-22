<?php 
	$js_patients = isset($js_patients) ? $js_patients : null;
	$patient = isset($patient) ? $patient : null;
?>
@section('list_script')
	<script type="text/javascript">
		function PatientListController($scope){
			$scope.patients = {{json_encode($js_patients)}};

			$scope.cancelPatientSelect = function(){
				parent.get('formVisible',true);
				parent.get('patientFrameVisible',false);
			}

			$scope.selectPatient = function(patient_id){
				parent.get('patient' , $scope.patients[patient_id]);
				parent.get('formVisible',true);
				parent.get('patientFrameVisible',false);
			}
		}
	</script>
@stop

@section('form_script')
	<script type="text/javascript">
			function PatientFormController($scope){
				$scope.cancelPatientSelect = function(){
					parent.get('formVisible',true);
					parent.get('patientFrameVisible',false);
					parent.reloadPatientFrame();
				}
			}
	</script>
@stop
