var layoutApp = angular.module('student', ['ngRoute', 'studentService', 'location_provider']);

// layoutApp.directive('getMessages', function() {
// 	return {
// 		link: function(scope) {
// 			$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' + scope.currentUser.id + "&secondUserId=").then(function(response) {

// 			});
// 		}
// 	}
// });

// layoutApp.config(['$routeProvider',function($routeProvider) {

//   $routeProvider
//   // chat window
//   .when("/ChatApp/chat/frontend/areas/student/student-profile.php/1" , {
//     templateUrl: "/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/Templates/Views/add_admin.php",
//     controller: "addAdmin"
//   })

//   .when("/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/Areas/Admin/User/Views/add_user.php/2", {
//     templateUrl: "/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/Templates/Views/add_cashier.php",
//     controller: "addCashier"
//   })
  
//   .when("/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/Areas/Admin/User/Views/add_user.php/3", {
//     templateUrl: "/CafeteriaApp/CafeteriaApp/CafeteriaApp.Frontend/Templates/Views/add_customer.php",
//     controller: "addCustomer"
//   })
  
// }]);

layoutApp.controller('chats', ['$scope', '$http', 'chat', '$rootScope', function($scope, $http, chat, $rootScope) {
	//$scope.message = "";

	// $scope.getChats = function() {
	// 	$scope.currentMessages = [];
	// 	$http.get('/ChatApp/chat/backend/requests/users.php?flag=1').then(function(response) {
	// 		$scope.chats = response.data;
	// 		console.log($scope.chats);
	// 		var length = $scope.chats.length;
	// 		for (var i = 0; i < length; i++) {
	// 			// $scope.currentMessages.push()
	// 			$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' + $scope.currentUser.id + "&secondUserId=" + $scope.chats[i].id + "&classId=" + 1).then(function(response) {
	// 				//return response.data;
	// 				//console.log(response.data);
	// 				$scope.currentMessages.push(response.data);
	// 				console.log(response);
	// 			});
	// 		}
	// 	});
	// };


 // $interval(function () {
 //     $http.get('/ChatApp/chat/backend/requests/users.php?flag=3').then(function(response) {

 //     });
 //    }, 1000);


	$scope.getCurrentInfo = function() {
		$http.get('/ChatApp/chat/backend/requests/users.php?flag=3').then(function(response) {
			$scope.currentUser = response.data;// id of user
			$scope.currentMessages = []; 
			$http.get('/ChatApp/chat/backend/requests/users.php?flag=1').then(function(response) {
				$scope.chats = response.data; // open windows
				var length = $scope.chats.length;
				if (length > 0) {
					for (var i = 0; i < length; i++) {
						$http.get('/ChatApp/chat/backend/requests/chat-messages.php?firstUserId=' + $scope.currentUser.id + "&secondUserId=" + $scope.chats[i].id + "&classId=" + 1).then(function(response) {
							if (response.data.length > 0) {
								var data = response.data;
								var len = data.length;
								for (var j = 0; j < len; j++) {
									$scope.currentMessages.push(data[j]);
									}
								console.log($scope.currentMessages);
							}
						});
					}
				}
			});
		});
		//console.log($scope.currentMessages );
	};

	$scope.getCurrentChatUser = function() {
		$http.get('/ChatApp/chat/backend/requests/users.php?flag=2').then(function(response) {
			$scope.currentChatUser = response.data;
		});
	};

	$scope.closeWindow = function(user) {
		$scope.chats.splice($scope.chats.indexOf(user), 1);
		$http.delete('/ChatApp/chat/backend/requests/users.php?id=' + user.id);
	};

	// $scope.getMessages = function(c) {
		
	// };
	
	//$scope.getChats();

	//$scope.getCurrentChatUser();
	$scope.getCurrentInfo();
	//$scope.getCurrentUser();

	//$scope.getMessages();

	$scope.$on('chatRequest', function() {
		$scope.getCurrentChatUser();		
		var x = 0;
		x = $scope.chats.filter(function(e) {
			return e.id == chat.chatUser.id;
		});
		if (x <= 0) { // if it's not in the array add it
			//console.log(chat.chatUser);
			$scope.chats.push(chat.chatUser);
			$http.put('/ChatApp/chat/backend/requests/users.php?flag=1', chat.chatUser).then(function(response) {
				$scope.getCurrentInfo();
			}); // put it in the session array of chats
		}
	});

	$scope.switchActiveUser = function(u) {
		$http.put('/ChatApp/chat/backend/requests/users.php?flag=2', u);
	};

	$scope.changeCurrentChatUser = function(u) {
		$scope.switchActiveUser(u);
		$scope.currentChatUser = u;
	};

	$scope.sendMessageToUser = function(user) {
		var data = {
			SentFrom: $scope.currentUser.id,
			SentTo: user.id,
			Content: user.message,
			ClassId: 1
		};
		//console.log($scope.message);
		//console.log(data);

		$http.post('/ChatApp/chat/backend/requests/chat-messages.php', data).then(function(response) {
			//console.log(response);
			$scope.getCurrentInfo();
		}, function(error) {
			//console.log(error);
		});

		user.message = "";
	};

	
	
}]);