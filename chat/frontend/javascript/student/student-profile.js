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

		$http.post('../../../backend/requests/chat-messages.php?flag=1');
	}

	$scope.messages = [];
	
	$rootScope.getAllMessages = function() {
		$http.get('../../../backend/requests/chat-messages.php?flag=2&offset=' + $scope.offset).then(function(response) {
			var d = response.data;
			var len = d.length;

			// iterate over last messages array
			for (var t = 0; t < len; t++) {
				var ind = $scope.messages.filter(function(e) {
					return e.id == d[t].id
				});

				if (ind.length > 0) {
					$scope.messages.splice($scope.messages.indexOf(ind[0]), 1);
					$scope.messages.push(d[t]);
				}
				else {
					$scope.messages.push(d[t]);
				}
			}
		});
	};

	$scope.getAllMessages();

	$scope.addChatWindow = function(user, m = 0) { // this will communicate with chats controller in layout
		if (m !== 0) {
			m.new = 0;
		}

		if ($rootScope.newMessages.indexOf(user.id) >= 0) {
			$rootScope.newLen--;
			$rootScope.newMessages.splice($rootScope.newMessages.indexOf(user.id), 1);
		}

		$http.post('../../../backend/requests/chat-messages.php?sentFrom=' + user.id);
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
							var len = data.length;

							for (var j = 0; j < len; j++) {
								scope.messages.unshift(data[j]); // add it to the last messages array
							}
						}
				});
			}

			elem.bind('mouseup', function() {
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