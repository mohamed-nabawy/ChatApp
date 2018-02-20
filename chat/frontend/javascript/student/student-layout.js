var layoutApp = angular.module('student', ['ngRoute', 'studentService', 'location_provider']);

layoutApp.directive('scrollToTop', ['$http', function($http) {
	return {
		link: function(scope, elem) {
			function loadAnother() {
				scope.offset += 10;

				// load another ten messages
				// scope.c is the current chat we scrolling
				$http.get('../../../backend/requests/chat-messages.php?firstUserId=' +
					scope.currentUser.id + "&secondUserId=" + scope.c.id + "&classId=" + 1 +
					"&offset=" + scope.offset).then(function(response) {
						if (response.data.length > 0) {
							elem[0].scrollTop += 50; // scroll down 50px
							var data = response.data; // older messages from database
							console.log(data);
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
			
			elem.bind('mouseup', function() {
				// make sure the element is at the top
				if (elem[0].scrollTop <= 25) {
					loadAnother();
				}
			});

			elem.bind('mousewheel', function() { // mousewheel (all browsers except firefox)
				if (elem[0].scrollTop <= 400) {
					loadAnother();
				}
			});

			elem.bind('DOMMouseScroll', function() { // firefox
				if (elem[0].scrollTop <= 5) {
					loadAnother();
				}
			});
		}
	}
}]);

layoutApp.directive('sendButton', function() {
	return {
		link: function(scope, elem) {
			elem.bind('keypress', function(e) {
				var t = elem[0].parentElement.previousSibling.previousSibling;
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

layoutApp.directive('message', ['$timeout', '$rootScope', function($timeout, $rootScope) {
	return {
		link: function(scope, elem) {
			if (scope.$parent.$last && scope.$last && scope.$parent.load == 1) { // only on load
				$timeout(function() {
					var z = $('.chat-window'); // chat window
					var y = $('.chat'); // chat window and the frame (close, name of chat user)
					var yLen = y.length;
					var zLen = z.length;
					
					for (var i = 0; i < zLen; i++) {
						z[i].scrollTop = z[i].scrollHeight - z[i].clientHeight; // scroll all chats to top
					}

					for (var i = 0; i < yLen; i++) {
						y[i].style.visibility = 'visible'; // make them all visible
					}

					$('.view-them')[0].style.visibility = 'visible'; // then make their container visible

					scope.$parent.load = 0;//flag (cancel)
				}, 1500);
			}
		}
	}
}]);

layoutApp.controller('chats', ['$scope', '$http', 'chat', '$rootScope', '$interval', '$timeout',
	function($scope, $http, chat, $rootScope, $interval, $timeout) {
		$scope.offset = 0;
		$scope.m = 0;
		$scope.load = 1;
		$rootScope.newLen = 0;
		$rootScope.newMessages = [];

		$scope.removefromNewMessagesIfAny = function(chat) {
			if ( $rootScope.newLen > 0 && $rootScope.newMessages.includes(chat.id) ) {
				$rootScope.newLen--;
				$rootScope.newMessages.splice( $rootScope.newMessages.indexOf(chat.id) );
			}

			$http.put('../../../backend/requests/chat-messages.php?sentFrom=' + chat.id).then(function(response) {
				console.log(response);
			});
		};

		$scope.newMessagesIds = [];

		$scope.getNotifications = function() {
			$http.get('../../../backend/requests/chat-messages.php?flag=3').then(function(response) {
				$scope.notifications = response.data;
				$rootScope.newLen = response.data.length;
				
				var newmes = response.data;				
				var thelen = newmes.length

				for (var h = 0; h < thelen; h++) {
					if ( !$rootScope.newMessages.includes(newmes[h].sentFrom) ) {
						$rootScope.newMessages.push(newmes[h].sentFrom);
					}
				}
			});
		};

		$scope.getNotifications();

		$scope.getNewMessages = function(flag = 0) {
			$http.get('../../../backend/requests/chat-messages.php').then(function(response) {
	     		if (response.data.length > 0) {
		     		var data = response.data;
		     		var len = data.length;

		     		if (flag == 0) {
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
			$http.get('../../../backend/requests/users.php?flag=3').then(function(response) {
				$scope.setCurrentUser(response.data);
				$scope.getChatsAndItsMessages();
			});
		};

		$scope.getChatsAndItsMessages = function() {
			// get chats array in the session and its messages
			$http.get('../../../backend/requests/users.php?flag=1').then(function(response) {
				var chats = response.data; // current chat windows
				var len = chats.length;
				$scope.chats = [];
				
				for (var i = 0; i < len; i++) {
					$scope.addChatMessages(chats[i].id, chats[i].firstName, i);
				}
			});
		};

		$scope.addChatMessages = function(id, firstName, k) {
			// load first ten messages
			$http.get('../../../backend/requests/chat-messages.php?firstUserId=' + $scope.currentUser.id + "&secondUserId=" + id + "&classId=" + 1 + "&offset=" + $scope.offset).then(function(response) {
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

					$scope.chats.unshift({ // at the start not at the end
						i: k,
						id: id,
						firstName: firstName,
						messages: data,
					});
				}
				else {
					$scope.chats.unshift({
						i: k,
						id: id,
						firstName: firstName,
						messages: [""]
					});			
				}

				if ($scope.m == 1) { // if chat added
					$timeout(function() {
						var z = $('.chat')[0];
						var y = $('.chat-window')[0];
						y.scrollTop = y.scrollHeight - y.clientHeight;
						z.style.visibility = 'visible';
						$scope.m = 0;
					}, 1000);
				}
			});
		};

		// initialize the current user
		$scope.setCurrentUser = function(data) {
			$scope.currentUser = data;
			//console.log(data);
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
			$http.delete('../../../backend/requests/users.php?id=' + userId);
		};
		
		$scope.getCurrentInfo();

		$scope.$on('chatRequest', function() {
			var x = 0;

			x = $scope.chats.filter(function(e) {
				return e.id == chat.chatUser.id;
			});

			if (x <= 0) {
				data = $rootScope.addedChat;
				$scope.m = 1;

				// should consider index so we can display chats correctly
				if ($scope.chats.length > 0) { // then make its index one less than the first one
					$scope.addChatMessages(data.id, data.firstName, $scope.chats[0].i - 1);
				}
				else { // make its index zero
					$scope.addChatMessages(data.id, data.firstName, 0);
				}
				
				$http.put('../../../backend/requests/users.php?flag=1', data);
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
			
			// empty the message again so user can start typing new messages
			user.message = "";

			// post request to add this message
			$http.post('../../../backend/requests/chat-messages.php', data).then(function(response) {
				console.log(response);
				data.id = parseInt(response.data); // id of new message
				var len = $scope.chats.length;

				// not sure if this is neccessary
				if (len >= 10) {
					$scope.chats.splice(0, 1); // remove the first message so we add the new one				
				}

				for (var i = 0; i < len; i++) {
					if ($scope.chats[i].id == user.id) { // get his receiver chat window
						$scope.chats[i].messages.unshift(data);
						//$scope.$emit('scrollToTop', i);
						setTimeout( function(){ 
							console.log($( "#chat"+user.id ).scrollTop());
							$( "#chat"+user.id ).scrollTop( $( "#chat"+user.id ).scrollTop()*2); },100);
							
						//$( "#"+user.id ).scrollTop($( "#"+user.id ).scrollTop());
						break;
					}
				}
			});
		};
}]);