layoutApp.controller('studentProfile', ['$scope', '$rootScope', '$http', 'chat', function($scope, $rootScope, $http, chat) {
	$scope.getMyClassMatesAndTeachers = function() {
		$http.get('../../../backend/requests/users.php').then(function(response) {
			$scope.myClassMatesAndTeachers = response.data;
		});
	};
	
	// $scope.getCurrentStudent = function() {
	// 	$http.get('../../../backend/requests/users.php?flag=1').then(function(response) {
	// 		$scope.currentStudent = response.data;
	// 		$scope.currentStudentId = $scope.currentStudent.id;
	// 		//console.log(response);
	// 	});
	// }
	$scope.getAllMessages = function() {
		$http.get('../../../backend/requests/chat-messages.php?flag=2').then(function(response) {
			$scope.messages = response.data;
		});
	};

	$scope.openChat = function(data) {
		user = {
			id: data.id,
			firstName: data.firstName,
		}

		$scope.addChatWindow(user);
	}

	$scope.getAllMessages();

	$scope.addChatWindow = function(user) { // this will communicate with chats controller in layout
		chat.chatUser = user;
		$rootScope.addedChat = user;
		$rootScope.$broadcast('chatRequest');
	};

	$scope.getMyClassMatesAndTeachers();
}]);