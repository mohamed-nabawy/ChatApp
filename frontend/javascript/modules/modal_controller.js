var modalApp = angular.module('modal',[]);

modalApp.controller('ModalController',['$scope','close','name',function($scope,close,name) {

	$scope.name = name;

 	$scope.close = function(result) {
		close(result,190);
  	};

	$scope.closeModal = function() {
  		close(null);
  	};

}]);