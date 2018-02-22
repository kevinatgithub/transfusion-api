function CancelController($scope,$http){

	$scope.cancel.reason = '';

	$scope.cancel.showForm = function(){
		$('#cancelRequestForm').modal('show');
	}

	$scope.cancel.perform = function(){
		if($scope.cancel.reason == ''){
			alert('Please provide a reason for cancelling this request');
			return;
		}
		$('#cancelRequestForm').modal('hide');
		$scope.cancel.processing = true;
		var formData = {
			bloodRequest : bloodRequest,
			reason : $scope.cancel.reason
		};

		$http.post(url_cancelPerform,formData).success(function(data){
			console.log(data);
			window.location.reload();
		});
	}
}