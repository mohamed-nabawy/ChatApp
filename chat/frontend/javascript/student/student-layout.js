var layoutApp = angular.module('student', []);

// loading another ten messages when scroll up
layoutApp.directive('scrollToTop', ['$http', '$rootScope', function($http, $rootScope) {
	return {
		link: function(scope, elem, attrs) {
			function loadAnother() {
				scope.offset += 10;

				// load another ten messages
				// scope.c is the current chat we scrolling
				$http.get('/chat/backend/requests/chat-messages.php?firstUserId=' +
					scope.c.firstUserId + "&secondUserId=" + scope.c.secondUserId + "&classId=" + 1 +
					"&offset=" + scope.offset).then(function(response) {
						if (response.data.length > 0) {
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
								
								scope.c.messages.push(data[j]); // add it to the chat messages
							}
						}
				});
			}

			elem.bind('wheel', function(e) {
				// check if it's moved from the wheel not from the scrollbar
				if (e.originalEvent.deltaY < 0) { // scroll up
					// if it's the top of the scroll, load another messages
					if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
						loadAnother();
					}

					// move the wheel up (remeber it's in the opposite direction)
					elem[0].scrollTop += 50;
					// prevent scroll from moving down by default at the end
            		e.preventDefault();
	            }
            	else if (e.originalEvent.deltaY > 0) { // scroll down
            		// move the wheel down (opposite direction)
					elem[0].scrollTop -= 50;
					// prevent scroll from moving up by default at the end
            		e.preventDefault();
            	}
			})

			elem.bind('scroll', function(e) {
				if (e.originalEvent.deltaY == undefined) {
					// check if it's the scroll bar not the chat messages
					if (e.target.nodeName == 'DIV') {
						// if it's at the top, load another messages
						if (elem[0].scrollTop >= elem[0].scrollHeight - 300) {
							loadAnother();
						}
					}
				}
			});
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

layoutApp.controller('chats', ['$scope', '$http', '$rootScope', '$interval', '$timeout',
	function($scope, $http, $rootScope, $interval, $timeout) {
		$scope.offset = 0;
		$rootScope.newLen = 0;
		$rootScope.newMessages = [];
		$scope.chats = [];

		$scope.showHideChat = function(ind) {
			var f = ($scope.chats[ind].open == 1) ? 0 : 1;
			
			// update the open state in the session
			$http.put('/chat/backend/requests/users.php?chatId=' + $scope.chats[ind].secondUserId + '&open=' + f).then(function(response) {
				$scope.chats[ind].open = (f == 1) ? 1 : 0;
			});
		}

		// reduce new messages notifications by one and make all messages read
		$scope.removefromNewMessagesIfAny = function(chat) {
			if ( $rootScope.newLen > 0 && $rootScope.newMessages.indexOf(chat.secondUserId) > -1) {
				$rootScope.newLen--;
				$rootScope.newMessages.splice( $rootScope.newMessages.indexOf(chat.secondUserId) );
			}

			$http.put('/chat/backend/requests/chat-messages.php?sentFrom=' + chat.secondUserId);
		};

		$scope.newMessagesIds = [];

		$scope.getNotifications = function() {
			$http.get('/chat/backend/requests/chat-messages.php?flag=3').then(function(response) {
				$scope.notifications = response.data;
				$rootScope.newLen = response.data.length;
				var newmes = response.data;
				var thelen = newmes.length;

				for (var h = 0; h < thelen; h++) {
					if ($rootScope.newMessages.indexOf(newmes[h].sentFrom) == -1) {
						$rootScope.newMessages.push(newmes[h].sentFrom);
					}
				}
			});
		};

		$scope.getNotifications();

		$scope.getNewMessages = function() {
			$http.get('/chat/backend/requests/chat-messages.php').then(function(response) {
	     		if (response.data.length > 0) {
		     		var data = response.data;
		     		var len = data.length;
		     		var chatLen = $scope.chats.length;

	     			for (var i = 0; i < len; i++) {
	     				for (var j = 0; j < chatLen; j++) {
	     					if (data[i].sentFrom == $scope.chats[j].secondUserId) {
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

	    $scope.$on('newMes', function() {
	    	var len = $scope.chats.length;

	    	for (var i = 0; i < len; i++) {
	    		if (($scope.chats[i].firstUserId == $rootScope.newMes.sentFrom && $scope.chats[i].secondUserId == $rootScope.newMes.sentTo) || ($scope.chats[i].firstUserId == $rootScope.newMes.sentTo && $scope.chats[i].secondUserId == $rootScope.newMes.sentFrom)) {
	    			var newMessage = {
	    				id: parseInt($rootScope.newMes.messageId),
						sentFrom: $rootScope.newMes.sentFrom,
						sentTo: $rootScope.newMes.sentTo,
						content: $rootScope.newMes.content,
						classId: 1
	    			};

	    			var g = $scope.chats[i].messages.filter(function(e) {
	    				return (e.id == newMessage.id);
	    			});

	    			if (g.length == 0) {
	    				$scope.chats[i].messages.push(newMessage);
	    			}
	    		}
	    	}
	    })

		$scope.getCurrentInfo = function() { // current info of user
			// get the user in the session
			$http.get('/chat/backend/requests/users.php?flag=3').then(function(response) {
				$scope.setCurrentUser(response.data);
				$scope.getChatsAndItsMessages();
			});
		};

		$scope.getChatsAndItsMessages = function() {
			// get chats array in the session and its messages
			$http.get('/chat/backend/requests/users.php?flag=1').then(function(response) {
				$scope.chats = response.data; // current chat windows
			});
		};

		$scope.addChatMessages = function(chat) {
			// load first ten messages
			$http.get('/chat/backend/requests/chat-messages.php?firstUserId=' + chat.firstUserId + "&secondUserId=" + chat.secondUserId + "&classId=" + 1 + "&offset=" + $scope.offset).then(function(response) {
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

				chat.messages = data;

				if ($scope.chats.length < 3) {
					$scope.chats.push(chat);
				}
				else {
					$scope.chats.splice(2, 0, chat);
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
				if ($scope.chats[i].secondUserId == userId) {
					$scope.chats.splice(i, 1);
					break;
				}
			}

			// delete it from the chats array in the session passing the chat user id
			$http.delete('/chat/backend/requests/users.php?id=' + userId);
		};
		
		$scope.getCurrentInfo();

		$scope.$on('chatRequest', function() {
			var x = 0;

			// check if user already in the chats array
			x = $scope.chats.filter(function(e) {
				return e.secondUserId == $rootScope.addedChat.secondUserId;
			});

			if (x.length == 0) {
				data = $rootScope.addedChat;
				$scope.addChatMessages(data);
				$http.put('/chat/backend/requests/users.php?flag=1', data);
			}
			else {
				if (x[0].open == 0) {
					$http.put('/chat/backend/requests/users.php?chatId=' + x[0].secondUserId + '&open=1').then(function(response) {
						x[0].open = 1;
					});
				}
			}
		});

		$scope.sendMessageToUser = function(user) {
			if (user.message != undefined && user.message != '') {
				// prepare the data
				var data = {
					sentFrom: user.firstUserId,
					sentTo: user.secondUserId,
					content: user.message,
					classId: 1
				};
				
				// empty the message again so user can start typing new messages
				user.message = "";

				// post request to add this message
				$http.post('/chat/backend/requests/chat-messages.php', data).then(function(response) {
					data.id = parseInt(response.data); // id of new message
					var len = $scope.chats.length;

					for (var i = 0; i < len; i++) {
						if ($scope.chats[i].secondUserId == user.id) { // get his receiver chat window
							if ($scope.chats[i].messages.length >= 10) {
								$scope.chats[i].messages.pop();
							}

							$scope.chats[i].messages.push(data);
							$("#chat" + user.id).scrollTop(0);
							break;
						}
					}
				});
			}
		};
}]);