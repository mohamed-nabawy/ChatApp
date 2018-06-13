var layoutApp = angular.module('student', ['studentService']);

layoutApp.directive('scrollToTop', ['$http', function($http) {
	return {
		link: function(scope, elem, attrs) {
			function loadAnother() {
				scope.offset += 10;

				// load another ten messages
				// scope.c is the current chat we scrolling
				$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' +
					scope.currentUser.id + "&secondUserId=" + scope.c.id + "&classId=" + 1 +
					"&offset=" + scope.offset).then(function(response) {
						if (response.data.length > 0) {
							elem[0].scrollTop -= 50; // scroll down 50px
							var data = response.data; // older messages from database
							var len = data.length;

							for (var j = 0; j < len; j++) {
								data[j] = {
									id: parseInt(data[j].id), // should parseInt first to display it correctly
									sentFrom: data[j].sentFrom,
									sentTo: data[j].sentTo,
									content: data[j].content,
									classId: 1
								};
								
								scope.c.messages.unshift(data[j]); // add it to the chat messages
							}
						}
				});
			}

			//elem.bind('scroll', function() {
				
				//console.log(34);
				elem.mouseup(function(e) {
					//console.log('mousescroll');
					//console.log(e.target.nodeName);
					if (e.target.nodeName == 'DIV') {
						scope.lastScrollUp = elem[0].scrollTop;
						scope.lastScrollDown = elem[0].scrollTop;

						if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
							loadAnother();
						}
					}
				});


			//});

			
			elem.bind('wheel', function(e) { // mousewheel event
				//console.log('wheel');
				if (e.originalEvent.deltaY < 0) { // scroll up
					if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
						loadAnother();
						scope.lastScrollUp += 200;
						scope.count++;
						return false;
					}

					scope.lastScrollUp += 50;
					scope.lastScrollDown = scope.lastScrollUp;
					elem[0].scrollTop = (scope.lastScrollUp);

            		e.preventDefault();
	            }
            	else if (e.originalEvent.deltaY > 0) { // scroll down
					if (elem[0].scrollTop == 0) {
						return false;
					}

					scope.lastScrollDown -= 50;
					scope.lastScrollUp = scope.lastScrollDown;
					elem[0].scrollTop = 1 * (scope.lastScrollDown);

            		for (var j = 0; j < scope.count; j++) {
						scope.lastScrollUp -= 200;
            		}

					scope.count = 0;

            		e.preventDefault();
            	}
			});
			
			// scope.$on('finished', function() {
			// 	console.log(57);
			// 	var x1 = attrs['id'];

			// 	document.getElementById(x1).onmouseup = function() {
			// 		console.log(26);
			// 		scope.lastScrollUp = elem[0].scrollTop;
			// 		scope.lastScrollDown = elem[0].scrollTop;

			// 		if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
			// 			loadAnother();
			// 		}
			// 	}
			// })

			// document.getElementById('chat3').onmouseup = function() {
			// 	scope.lastScrollUp = elem[0].scrollTop;
			// 	scope.lastScrollDown = elem[0].scrollTop;

			// 	if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
			// 		loadAnother();
			// 	}
			// }
			//elem.bind('scroll', function() {
				

			//})
			
		}
	}
}]);

layoutApp.directive('sendButton', function() {
	return {
		link: function(scope, elem) {
			elem.bind('keypress', function(e) {
				if (e.which == 13) { // enter key
					var user = scope.c; // get current chat
					scope.sendMessageToUser(user);
					user.message = ""; // make chat message empty
					e.preventDefault(); // no new line
				}
			});
		}
	}
});

layoutApp.controller('chats', ['$scope', '$http', 'chat', '$rootScope', '$interval', '$timeout',
	function($scope, $http, chat, $rootScope, $interval, $timeout) {
		$scope.offset = 0;
		$rootScope.newLen = 0;
		$rootScope.newMessages = [];
		$scope.chats = [];
		$scope.count = 0;
		$scope.lastScrollUp = 0;
		$scope.lastScrollDown = 0;

		$scope.removefromNewMessagesIfAny = function(chat) {
			if ( $rootScope.newLen > 0 && $rootScope.newMessages.indexOf(chat.id) > -1) {
				$rootScope.newLen--;
				$rootScope.newMessages.splice( $rootScope.newMessages.indexOf(chat.id) );
			}

			$http.put('/ChatApp/chat/backend/requests/chat-messages.php?sentFrom=' + chat.id).then(function(response) {
				//console.log(response);
			});
		};

		$scope.newMessagesIds = [];

		$scope.getNotifications = function() {
			$http.get('/ChatApp/chat/backend/requests/chat-messages.php?flag=3').then(function(response) {
				$scope.notifications = response.data;
				$rootScope.newLen = response.data.length;				
				var newmes = response.data;				
				var thelen = newmes.length

				for (var h = 0; h < thelen; h++) {
					if ($rootScope.newMessages.indexOf(newmes[h].sentFrom) == -1) {
						$rootScope.newMessages.push(newmes[h].sentFrom);
					}
				}
			});
		};

		$scope.getNotifications();

		$scope.getNewMessages = function() {
			$http.get('/ChatApp/chat/backend/requests/chat-messages.php').then(function(response) {
	     		if (response.data.length > 0) {
		     		var data = response.data;
		     		var len = data.length;
		     		var chatLen = $scope.chats.length;

	     			for (var i = 0; i < len; i++) {
	     				for (var j = 0; j < chatLen; j++) {
	     					if (data[i].sentFrom == $scope.chats[j].id) {
	     						var d = {
	     							id: parseInt(data[i].id),
									sentFrom: data[i].sentFrom,
									sentTo: data[i].sentTo,
									content: data[i].content,
									classId: 1
	     						};

	     						$scope.newids = $scope.chats[j].messages.filter(function(e) {
	     							return e.id == d.id;
	     						});

	     						if ($scope.newids.length == 0) {
	     							$scope.chats[j].messages.push(d);
	     						};
	     					}
	     				}
	     			}
	     		}
	    	});
		};

	  	$interval(function () {
	  		$rootScope.getAllMessages();
	  		$scope.getNotifications();
	  		
		 	if ($scope.chats.length > 0) { // for open chat windows >> only refresh
		 		// check if there is a new received message
		 		$scope.getNewMessages();
		 	}
	    }, 3000);

		$scope.getCurrentInfo = function() { // current info of user
			// get the user in the session
			$http.get('/ChatApp/chat/backend/requests/users.php?flag=3').then(function(response) {
				$scope.setCurrentUser(response.data);
				$scope.getChatsAndItsMessages();
			});
		};

		$scope.getChatsAndItsMessages = function() {
			// get chats array in the session and its messages
			$http.get('/ChatApp/chat/backend/requests/users.php?flag=1').then(function(response) {
				console.log(response);
				$scope.chats = response.data; // current chat windows
			});
		};

		$scope.addChatMessages = function(id, firstName) {
			// load first ten messages
			$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' + $rootScope.currentUser.id + "&secondUserId=" + id + "&classId=" + 1 + "&offset=" + $scope.offset).then(function(response) {
				if (response.data.length > 0) {
					var data = response.data;
					var len = data.length;

					for (var i = 0; i < len; i++) {
						data[i] = {
							id: parseInt(data[i].id),
							sentFrom: data[i].sentFrom,
							sentTo: data[i].sentTo,
							content: data[i].content,
							classId: 1
						};
					}

					if ($scope.chats.length < 3) {
						$scope.chats.push({
							id: id,
							firstName: firstName,
							messages: data
						});
					}
					else {
						$scope.chats.splice(2, 0, {
							id: id,
							firstName: firstName,
							messages: data,
						});
					}
				}
				else {
					if ($scope.chats.length < 3) {
						$scope.chats.push({
							id: id,
							firstName: firstName,
							messages: data
						});
					}
					else {
						$scope.chats.splice(2, 0, {
							id: id,
							firstName: firstName,
							messages: data,
						});
					}
				}
			});
		};

		// initialize the current user
		$scope.setCurrentUser = function(data) {
			$rootScope.currentUser = data;
		};

		// close chat window of a user
		$scope.closeWindow = function(userId, ind) {
			var len = $scope.chats.length;

			// remove it from the chats array
			for (var i = 0; i < len; i++) {
				if ($scope.chats[i].id == userId) {
					$scope.chats.splice(i, 1);
					break;
				}
			}

			// delete it from the chats array in the session passing the chat user id
			$http.delete('/ChatApp/chat/backend/requests/users.php?id=' + userId);
		};
		
		$scope.getCurrentInfo();

		$scope.$on('chatRequest', function() {
			var x = 0;

			// check if user already in the chats array
			x = $scope.chats.filter(function(e) {
				return e.id == chat.chatUser.id;
			});

			if (x <= 0) {
				data = $rootScope.addedChat;
				$scope.addChatMessages(data.id, data.firstName);
				$http.put('/ChatApp/chat/backend/requests/users.php?flag=1', data);
			}
		});

		$scope.sendMessageToUser = function(user) {
			if (user.message != undefined && user.message != '') {
				// prepare the data
				var data = {
					sentFrom: $rootScope.currentUser.id,
					sentTo: user.id,
					content: user.message,
					classId: 1
				};
				
				// empty the message again so user can start typing new messages
				user.message = "";

				// post request to add this message
				$http.post('/ChatApp/chat/backend/requests/chat-messages.php', data).then(function(response) {
					data.id = parseInt(response.data); // id of new message
					var len = $scope.chats.length;

					for (var i = 0; i < len; i++) {
						if ($scope.chats[i].id == user.id) { // get his receiver chat window
							if ($scope.chats[i].messages.length >= 10) {
								$scope.chats[i].messages.pop();
								$scope.chats[i].messages.unshift(data);
							}

							else {
								$scope.chats[i].messages.unshift(data);
							}
							
							$("#chat" + user.id).scrollTop(0);

							break;
						}
					}
				});

				//console.log(12);
			}
		};
}]);