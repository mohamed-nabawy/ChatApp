layoutApp.controller('studentProfile', ['$scope', '$rootScope', '$http', 'messageService', 'userService', function($scope, $rootScope, $http, messageService, userService) {
	$scope.messages = [];
	$scope.offset = 0;
	$scope.imageUrl = '';
	$scope.csrf_token = document.getElementById('csrf_token').value;

	$scope.updateImage = function() {
		if ($scope.myform.$valid) {
			var imageData = {
				Image: $('#myimg').attr('src'),
				csrf_token: $scope.csrf_token,
				x1: $('#x1').val(),
				y1: $('#y1').val(),
				w: $('#w').val(),
				h: $('#h').val()
			};

			$('#myModal').hide();

			userService.updateUserImage(imageData).then(function(data) {
				// console.log(response);
				// $scope.i++;
				// $('#cropped').html($('#cropped').attr('src'));
				// $('#cropped').attr('src', $('#cropped').attr('src') + '?' + $scope.i);
				// $('#croppedLayout').attr('src', $('#croppedLayout').attr('src') + '?' + $scope.i);
				location.reload();
			});
		}
	};

	$scope.delete = function() {
		userService.deleteUserImage().then(function(data) {
			location.reload();
		});
	};

	$scope.getMyClassMatesAndTeachers = function() {	
		userService.getMyClassMatesAndTeachers().then(function(data) {
			$scope.myClassMatesAndTeachers = data;
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

	$scope.setAllNotificationsToRead = function($event) {
		$rootScope.newMessages = [];
		$rootScope.newLen = 0;
		
		if ($scope.lastMessagesClicked == 0) {
			$scope.lastMessagesClicked = 1;
		}
		else {
			$scope.lastMessagesClicked = 0;
		}
		
		$event.stopPropagation();
		$rootScope.newMessages = [];
		$rootScope.newLen = 0;
		messageService.markAllMessageNotificationsAsRead();
	};
	
	$rootScope.getAllMessages = function() {
		messageService.getLastCurrentUserMessages(0).then(function(data) {
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
						var cond = false;

						if ($scope.messages[j])
							cond = ($scope.messages[j].sentFrom == data[i].sentFrom && $scope.messages[j].sentTo == data[i].sentTo) || ($scope.messages[j].sentFrom == data[i].sentTo && $scope.messages[j].sentTo == data[i].sentFrom);

						if (cond)
							$scope.messages.splice(j, 1);

					}

					$scope.messages.push(data[i]);
					$rootScope.newMes = data[i];
					$rootScope.$broadcast('newMes');
				}
			}
		});
	};

	$scope.$on('updatenew', function(e, id) {
		var lenMessages = $scope.messages.length;

		for (var k = 0; k < lenMessages; k++) {
			if ($scope.messages[k].id == id) {
				$scope.messages[k].new = 0;

				break;
			}
		}
	});

	$scope.getAllMessages();

	$scope.addChatWindow = function(data) { // this will communicate with chats controller in layout
		//console.log(data);

		// data represents info about about the remote user (id, firstname, etc) and info about the message(sentfrom, sentto, etc) 

		
		var user = {
			firstUserId: $rootScope.currentUser.id,
			secondUserId: data.id,
			firstName: data.firstName,
			open: 1
		};

		// if ($rootScope.newMessages.indexOf(user.id) > 0) {
		// 	$rootScope.newLen--;
		// 	$rootScope.newMessages.splice($rootScope.newMessages.indexOf(user.id), 1);
		// }

		// var lenMes = $scope.messages.length;

		// for (var j = 0; j < lenMes; j++) {
		// 	if ($scope.messages[j].sentFrom == user.id) {
		// 		$scope.messages[j].new = 0;
		// 		break;
		// 	}
		// }

		// mark messages as read
		if (data.sentTo == $rootScope.currentUser.id && data.new == 1)
			data.new = 0; // now it's old
			messageService.markMessagesAsReadFromUser(data.id);
		
		$rootScope.addedChat = user;
		$rootScope.$broadcast('chatRequest');
	};

	$scope.getMyClassMatesAndTeachers();
}]);

layoutApp.directive('scrollToDown', ['$http', 'messageService', function($http, messageService) {
	return {
		link: function(scope, elem) {
			function loadAnother() {
				scope.offset += 10;

				// load another ten messages
				// scope.c is the current chat we scrolling
				messageService.getLastCurrentUserMessages(scope.offset).then(function(data) {
						if (data.length > 0) {
							elem[0].scrollTop -= 50; // scroll up 50px
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