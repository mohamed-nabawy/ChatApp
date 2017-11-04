var layoutApp = angular.module('student', ['ngRoute', 'studentService', 'location_provider']);

layoutApp.directive('scrollToTop',['$http', function($http) {
	return {
		link: function(scope, elem, attr) {

			elem.bind('scroll', function() { // on scroll event				
				if (elem[0].scrollTop <= 5) { // make sure the element is at the top
					elem.bind('mouseup', function() {
						scope.offset += 10;
						// load another ten messages
						$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' +
							scope.currentUser.id + "&secondUserId=" + attr.scrollToTop + "&classId=" + 1 + "&offset=" + scope.offset).then(function(response) {
							if (response.data.length > 0) {
								console.log(response);
								elem[0].scrollTop += 50;
								var data = response.data;
								var len = data.length;
								for (var j = 0; j < len; j++) {
									data[j]['id'] = parseInt(data[j]['id']);
									scope.currentMessages.push(data[j]);
								}
							}
						});
					});	
				}
			});
		}
	}
}]);

// layoutApp.directive('last', function($rootScope) {
// 	return {
// 		link: function(scope) {
// 			//if (scope.$last == true) {
// 				//scope.$broadcast("1");
// 			//}
// 		}
// 	}
// });

layoutApp.directive('finished', ['$timeout', function($timeout) {
	return {
		link: function(scope, elem, attr) {
			if (scope.$last == true) { // if the current element is the last
				$timeout(function() {
					var z = elem[0].parentElement; // whole chat window
					//var x = z.parentElement;	
					z.scrollTop = z.scrollHeight - z.clientHeight;
					//console.log(z.scrollTop);
				});
			}
		}
	}
}]);

layoutApp.controller('chats', ['$scope', '$http', 'chat', '$rootScope', '$interval', '$timeout', function($scope, $http, chat, $rootScope, $interval, $timeout) {

	$scope.offset = 0;

 	$interval(function () {
	 	if ($scope.chats.length > 0) { // for open chat windows >> only refresh
	 		// check if there is a new received message
	     	$http.get('/ChatApp/chat/backend/requests/chat-messages.php').then(function(response) {
				//if ( parseInt(response.data) > 0 ) {
					//$scope.addNewMessages();
					//$scope.getCurrentInfo();
				//}
	    	});
	 	}
    }, 3000);

	$scope.getCurrentInfo = function() {
		$http.get('/ChatApp/chat/backend/requests/users.php?flag=3').then(function(response) {
			$scope.setCurrentUser(response.data);
			$http.get('/ChatApp/chat/backend/requests/users.php?flag=1').then(function(response) {
				$scope.chats = response.data;
				//console.log($scope.chats);
				$scope.addNewMessages();
			});
		});
	};

	$scope.addNewMessages = function() {
		var length = $scope.chats.length;
		if (length > 0) {
			for (var i = 0; i < length; i++) {
				$scope.addChatMessages($scope.chats[i].id);
			}
		}
	};

	$scope.addChatMessages = function(id) {
		$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' + $scope.currentUser.id + "&secondUserId=" + id + "&classId=" + 1 + "&offset=" + $scope.offset).then(function(response) {
			if (response.data.length > 0) {
				var data = response.data;
				var len = data.length;
				for (var j = 0; j < len; j++) {
					data[j]['id'] = parseInt(data[j]['id']);
					$scope.currentMessages.push(data[j]);
				}
			}
		});
	};

	$scope.getCurrentMessages = function(chatUserId) {
		return $scope.currentMessages.filter(function(e) {
			return e.sentFrom == chatUserId || e.sentTo == chatUserId;
		});
	};

	$scope.setCurrentUser = function(data) {
		$scope.currentUser = data;// id of user
		$scope.currentMessages = [];
	};

	$scope.closeWindow = function(user) {
		$scope.chats.splice($scope.chats.indexOf(user), 1);
		$http.delete('/ChatApp/chat/backend/requests/users.php?id=' + user.id);
	};
	
	$scope.getCurrentInfo();

	$scope.$on('chatRequest', function() {
		//console.log(1);
		var x = 0;
		x = $scope.chats.filter(function(e) {
			return e.id == chat.chatUser.id;
		});
		if (x <= 0) { // if it's not in the array add it
			//console.log(1);
			$scope.chats.push(chat.chatUser);
			$http.put('/ChatApp/chat/backend/requests/users.php?flag=1', chat.chatUser).then(function(response) {
				//console.log(1);
				$scope.getCurrentInfo();
				//$scope.addChatMessages(chat.chatUser.id);
			}); // put it in the session array of chats
		}
	});

	$scope.sendMessageToUser = function(user) {
		var data = {
			SentFrom: $scope.currentUser.id,
			SentTo: user.id,
			Content: user.message,
			ClassId: 1
		};

		$http.post('/ChatApp/chat/backend/requests/chat-messages.php', data).then(function(response) {
			//$scope.currentMessages.push(data);
			//console.log(response);
			$scope.getCurrentInfo();

			//$scope.addChatMessages(user.id);
		});
		$scope.currentMessages.push(data);
		//console.log($scope.currentMessages[0]);
		//console.log(data);
		user.message = "";
	};
}]);