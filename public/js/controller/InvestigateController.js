function InvestigateController($scope,$http){
	
	$scope.investigate.cancelChanges = function(){
		$scope.investigate.editMode = false;
	}

	$scope.investigate.showReactionForm = function(detail){
		$scope.investigate.current = detail;
		$scope.investigate.loading = true;
		$http.post(url_getDetailReactions,{'detail' : detail}).success(function(data){
			//console.log(data);
			$scope.investigate.current.reactions = data.reactions;
			$scope.investigate.editMode = true;
			$scope.investigate.loading = false;
		});
	}

	$scope.investigate.saveReactionSelection = function(){
		$scope.investigate.loading = true;
		$http.post(url_saveDetailReactions,{'detail' : $scope.investigate.current}).success(function(data){
			console.log(data);
			$scope.investigate.current.reactions = data.reactions;
			$scope.investigate.editMode = false;
			$scope.investigate.loading = false;
		});
	}

	$scope.investigate.verifySelection = function(reaction_id){
		if($scope.investigate.current.reactions[reaction_id] == undefined || $scope.current.reactions[reaction_id] == false){
			return false;
		}else if($scope.investigate.current.reactions[reaction_id] == true){
			return true;
		}
	}
}