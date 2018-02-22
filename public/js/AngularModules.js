angular.module('BloodRequest',['PatientServices'],function($interpolateProvider){
			$interpolateProvider.startSymbol('<%');
		    $interpolateProvider.endSymbol('%>');
		});
angular.module('PatientServices',['ngResource'])
		.factory('Patient',function($resource){
			return $resource('/Patient/apilist');
		});