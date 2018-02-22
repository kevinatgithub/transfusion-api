<?php 
	$js_physicians = isset($js_physicians) ? $js_physicians : null;
	$physician = isset($physician) ? $physician : null;
?>
@section('list_script')
	<script type="text/javascript">
		function PhysicianListController($scope){
			$scope.physicians = {{json_encode($js_physicians)}};

			$scope.cancelPhysicianSelect = function(){
				parent.get('formVisible',true);
				parent.get('physicianFrameVisible',false);
			}

			$scope.selectPhysician = function(physician_id){
				attending_physician = $scope.physicians[physician_id];
				parent.get('physician' , attending_physician);
				parent.get('formVisible',true);
				parent.get('physicianFrameVisible',false);
				attending_physician_name = attending_physician.fname + ' ' + attending_physician.mname + ' ' + attending_physician.lname;
				parent.get('attending_physician',attending_physician_name);
			}
		}
	</script>
@stop

@section('form_script')
	<script type="text/javascript">
			function PhysicianFormController($scope){
				$scope.cancelPhysicianSelect = function(){
					parent.get('formVisible',true);
					parent.get('physicianFrameVisible',false);
					parent.reloadPhysicianFrame();
				}
			}
	</script>
@stop
