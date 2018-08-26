layoutApp.controller('studentProfile', ['$scope', '$rootScope', '$http', function($scope, $rootScope, $http) {
	$scope.messages = [];

	$scope.imageUrl = '';
	$scope.csrf_token = document.getElementById('csrf_token').value;

	$scope.updateImage = function() {
		if ($scope.myform.$valid) {
			var data = {
				Image: $('#myimg').attr('src'),
				csrf_token: $scope.csrf_token,
				x1: $('#x1').val(),
				y1: $('#y1').val(),
				w: $('#w').val(),
				h: $('#h').val()
			};

			$('#myModal').hide();

			$http.post('/chat/backend/requests/users.php?update=1', data).then(function(response) {
				console.log(response);
				//$scope.i++;
				//$('#cropped').html($('#cropped').attr('src'));
				// $('#cropped').attr('src', $('#cropped').attr('src') + '?' + $scope.i);
				// $('#croppedLayout').attr('src', $('#croppedLayout').attr('src') + '?' + $scope.i);
				location.reload();
			});
		}
	};

	$scope.delete = function() {
		$http.delete('/chat/backend/requests/users.php?f=1').then(function(response) {
			location.reload();
		});
	};

	$scope.getMyClassMatesAndTeachers = function() {	
		$http.get('/chat/backend/requests/users.php').then(function(response) {
			$scope.myClassMatesAndTeachers = response.data;
		});
	};

	$scope.lastMessagesClicked = 0;

	$(window).click(function(e) {
		if (e.target.classList[0] != 'panel' && $scope.lastMessagesClicked == 1) {
			$scope.$apply(function() {
				$scope.lastMessagesClicked = 0;
			})
		}
	})

	$scope.offset = 0;

	$scope.setAllNotificationsToRead = function($event) {
		if ($scope.lastMessagesClicked == 0) {
			$scope.lastMessagesClicked = 1;
		}
		else {
			$scope.lastMessagesClicked = 0;
		}
		
		$event.stopPropagation();
		$rootScope.newMessages = [];
		$rootScope.newLen = 0;
		$http.put('/chat/backend/requests/chat-messages.php?flag=1');
	}
	
	$rootScope.getAllMessages = function() {
		$http.get('/chat/backend/requests/chat-messages.php?flag=2&offset=0').then(function(response) {
			var data = response.data;
			var len = data.length;
			var lenMes = $scope.messages.length;

			for (var i = 0; i < len; i++) {
				var f = 0;
				
				for (var j = 0; j < lenMes; j++) {
					if ($scope.messages[j].messageId == data[i].messageId) {
						if (data[i].new != $scope.messages[j].new) {
							$scope.messages[j].new = data[i].new;
						}
						
						f = 1;
						break;
					}
				}

				if (f == 0) {
					for (var j = 0; j < lenMes; j++) {
						if ($scope.messages[j].sentFrom == data[i].sentFrom) {
							$scope.messages.splice(j, 1);
							break;
						}
					}

					$scope.messages.push(data[i]);
					$rootScope.newMes = data[i];
					$rootScope.$broadcast('newMes');
				}
			}
		});
	};

	$scope.getAllMessages();

	$scope.addChatWindow = function(data) { // this will communicate with chats controller in layout
		var user = {
			firstUserId: $rootScope.currentUser.id,
			secondUserId: data.id,
			firstName: data.firstName,
			open: 1
		};

		if ($rootScope.newMessages.indexOf(user.id) > 0) {
			$rootScope.newLen--;
			$rootScope.newMessages.splice($rootScope.newMessages.indexOf(user.id), 1);
		}

		var lenMes = $scope.messages.length;

		for (var j = 0; j < lenMes; j++) {
			if ($scope.messages[j].sentFrom == user.id) {
				$scope.messages[j].new = 0;
				break;
			}
		}

		// mark messages as read
		$http.put('/chat/backend/requests/chat-messages.php?sentFrom=' + user.id);
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
				$http.get('/chat/backend/requests/chat-messages.php?flag=2&offset=' +
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

			elem.bind('scroll', function(e) {
				// make sure the element is at the top
				// check if it's not the wheel (only the scrollbar)
				if (e.originalEvent.deltaY == undefined) {
					if (elem[0].scrollTop <= 150) {
						loadAnother();
					}
				}
			});

			elem.bind('wheel', function() { // mousewheel event
				if (elem[0].scrollTop <= 150) {
					loadAnother();
				}
			});
		}
	};
}]);