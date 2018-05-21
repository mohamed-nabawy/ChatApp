layoutApp.controller('studentProfile', ['$scope', '$rootScope', '$http', 'chat', function($scope, $rootScope, $http, chat) {
	$scope.getMyClassMatesAndTeachers = function() {
		$http.get('../../../backend/requests/users.php').then(function(response) {
			$scope.myClassMatesAndTeachers = response.data;
		});
	};

	$scope.offset = 0;

	$scope.setAllNotificationsToRead = function() {
		$rootScope.newMessages = [];
		$rootScope.newLen = 0;

		$http.put('../../../backend/requests/chat-messages.php?flag=1').then(function(response) {
			//console.log(response);
		});
	}
	
	$rootScope.getAllMessages = function() {
		$http.get('../../../backend/requests/chat-messages.php?flag=2&offset=' + $scope.offset).then(function(response) {
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
		if ($rootScope.newMessages.indexOf(user.id) > 0) {
			$rootScope.newLen--;
			$rootScope.newMessages.splice($rootScope.newMessages.indexOf(user.id), 1);
		}

		$http.put('../../../backend/requests/chat-messages.php?sentFrom=' + user.id);
		chat.chatUser = user;
		$rootScope.addedChat = user;
		$rootScope.$broadcast('chatRequest');
	};

	$scope.getMyClassMatesAndTeachers();
}]);

layoutApp.directive('scrollToDown', ['$http', function($http) {
	return {
		link: function(scope, elem) {
			function loadAnother() {
				scope.offset += 10;

				// load another ten messages
				// scope.c is the current chat we scrolling
				$http.get('../../../backend/requests/chat-messages.php?flag=2&offset=' +
					scope.offset).then(function(response) {
						if (response.data.length > 0) {
							elem[0].scrollTop -= 50; // scroll up 50px
							var data = response.data; // older last messages from database
							console.log(data);
							var len = data.length;

							for (var j = 0; j < len; j++) {
								scope.messages.unshift(data[j]); // add it to the last messages array
							}
						}
				});
			}

			elem.bind('mouseup', function() {
				console.log(elem[0].scrollTop);
				// make sure the element is at the top
				if (elem[0].scrollTop <= 150) {
					loadAnother();
				}
			});

			elem.bind('mousewheel', function() { // mousewheel (all browsers except firefox)
				if (elem[0].scrollTop <= 150) {
					loadAnother();
				}
			});

			elem.bind('DOMMouseScroll', function() { // firefox
				if (elem[0].scrollTop <= 150) {
					loadAnother();
				}
			});
		}
	};
}]);