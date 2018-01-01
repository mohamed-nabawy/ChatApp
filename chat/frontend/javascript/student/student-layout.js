var layoutApp = angular.module('student', ['ngRoute', 'studentService', 'location_provider']);

layoutApp.directive('scrollToTop', ['$http', function($http) {
	return {
		link: function(scope, elem, attr) {

			elem.bind('scroll', function() { // on scroll event				
				if (elem[0].scrollTop <= 5) { // make sure the element is at the top
					elem.bind('mouseup', function() {
						scope.offset += 10;
						// load another ten messages

						$http.get('../../../backend/requests/chat-messages.php?firstUserId=' +
							scope.currentUser.id + "&secondUserId=" + attr.scrollToTop + "&classId=" + 1 +
							"&offset=" + scope.offset).then(function(response) {
								if (response.data.length > 0) {
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

layoutApp.directive('sendButton', function() {
	return {
		link: function(scope, elem, attr) {
			elem.bind('keypress', function(e) {
				if (e.which == 13) { // enter key
					scope.sendMessageToUser( JSON.parse(attr.sendButton) ); // parse string as JSON first
					e.preventDefault(); // no new line
				}
			});
		}
	}
})

// doesn't work properly ???
layoutApp.directive('finished', ['$timeout', '$rootScope', function($timeout, $rootScope) {
	return {
		link: function(scope, elem, attr) {
			if (scope.$parent.$last && scope.$last) {
				$timeout(function() {
					var z = $('.chat-window'); // whole chat window
					
					for (var i = 0; i < z.length; i++) {
						z[i].scrollTop = z[i].scrollHeight - z[i].clientHeight;
					}

					$('.view-them')[0].style.visibility = 'visible';
				}, 1000);
			}
		}
	}
}]);

layoutApp.controller('chats', ['$scope', '$http', 'chat', '$rootScope', '$interval', '$timeout',
	function($scope, $http, chat, $rootScope, $interval, $timeout) {
		$scope.offset = 0;

	 	$interval(function () {
		 	if ($scope.chats.length > 0) { // for open chat windows >> only refresh
		 		// check if there is a new received message
		     	$http.get('../../../backend/requests/chat-messages.php').then(function(response) {
		    	});
		 	}
	    }, 3000);

		$scope.getCurrentInfo = function() { // current info of user
			// get the user in the session
			$http.get('../../../backend/requests/users.php?flag=3').then(function(response) {
				$scope.setCurrentUser(response.data);
				$scope.getChatsAndItsMessages();
			});
		};

		$scope.getChatsAndItsMessages = function() {
			// get chats array in the session and its messages
			$http.get('../../../backend/requests/users.php?flag=1').then(function(response) {
				$scope.chats = response.data; // current chat windows
				$scope.addAllChatsMessages();
			});
		};

		$scope.addAllChatsMessages = function() {
			var length = $scope.chats.length;

			if (length > 0) {
				for (var i = 0; i < length; i++) {
					$scope.addChatMessages($scope.chats[i].id); // add that chat messages
				}
			}
		};

		$scope.addChatMessages = function(id) { // id of the user of the chat
			// load first ten messages
			$http.get('../../../backend/requests/chat-messages.php?firstUserId=' + $scope.currentUser.id + "&secondUserId=" + id + "&classId=" + 1 + "&offset=" + $scope.offset).then(function(response) {
				if (response.data.length > 0) {
					var data = response.data;
					var len = data.length;

					for (var j = 0; j < len; j++) {
						data[j]['id'] = parseInt(data[j]['id']); // convert it to int
						$scope.currentMessages.push(data[j]);
					}
				}
			});
		};

		// filter current messages according to the chat user id.
		// this will be used in each item in the ng-repeat of chats
		$scope.getCurrentMessages = function(chatUserId) {
			return $scope.currentMessages.filter(function(e) {
				return e.sentFrom == chatUserId || e.sentTo == chatUserId;
			});
		};

		// initialize the current user
		$scope.setCurrentUser = function(data) {
			$scope.currentUser = data; // id of user
			$scope.currentMessages = []; // initial empty array of current messages
		};

		// close chat window of a user
		$scope.closeWindow = function(user) {
			// remove it from the chats array
			$scope.chats.splice($scope.chats.indexOf(user), 1);
			// delete it from the chats array in the session passing the chat user id
			$http.delete('../../../backend/requests/users.php?id=' + user.id);
		};
		
		$scope.getCurrentInfo();

		$scope.$on('chatRequest', function() {
			var x = 0;

			x = $scope.chats.filter(function(e) {
				return e.id == chat.chatUser.id;
			});

			if (x <= 0) { // if it's not in the array add it
				$scope.chats.push(chat.chatUser);
				
				$http.put('../../../backend/requests/users.php?flag=1', chat.chatUser).then(function(response) {
					$scope.getCurrentInfo();
				}); // put it in the session array of chats
			}
		});

		$scope.sendMessageToUser = function(user) {
			// prepare the data
			var data = {
				sentFrom: $scope.currentUser.id,
				sentTo: user.id,
				content: user.message,
				classId: 1
			};

			// post request to add this message
			$http.post('../../../backend/requests/chat-messages.php', data).then(function(response) {
				console.log(response);
				$scope.getCurrentInfo(); // update current info ???
			});

			// empty the message again so user can start typing new messages
			user.message = "";
		};
}]);