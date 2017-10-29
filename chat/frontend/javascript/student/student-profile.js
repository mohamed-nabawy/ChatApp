layoutApp.controller('studentProfile', ['$scope', '$rootScope', '$http', 'chat', function($scope, $rootScope, $http, chat) {

	$scope.getMyClassMatesAndTeachers = function() {
		$http.get('../../../backend/requests/users.php').then(function(response) {
			$scope.myClassMatesAndTeachers = response.data;
			//console.log(response);
		});
	};
	
	// $scope.getCurrentStudent = function() {
	// 	$http.get('../../../backend/requests/users.php?flag=1').then(function(response) {
	// 		$scope.currentStudent = response.data;
	// 		$scope.currentStudentId = $scope.currentStudent.id;
	// 		//console.log(response);
	// 	});
	// }

	$scope.addChatWindow = function(user) { // this will communicate with chats controller in layout
		chat.chatUser = user;
		$http.put('../../../backend/requests/users.php?flag=2', user).then(function(response) { // change current active chat user
			$rootScope.$broadcast('chatRequest');
		});		
	};

	$scope.getMyClassMatesAndTeachers();

}]);