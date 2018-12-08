var messageServiceApp = angular.module('message-service', []);

messageServiceApp.factory('messageService', ['$http', '$q', function($http, $q) {
    var messageServiceObj = {};

    messageServiceObj.getMessagesMaxTen = function(firstUserId, secondUserId, offset) {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/chat-messages.php?firstUserId=' +
				firstUserId + "&secondUserId=" + secondUserId + "&classId=" + 1 +
				"&offset=" + offset).then(function(response) {
                    resolve(response.data);  
            });
        });
    };

    messageServiceObj.markMessagesAsReadFromUser = function(secondUserId) {
        return $q(function(resolve, reject) {
            $http.put('/chat/backend/requests/chat-messages.php?sentFrom=' + secondUserId);
        });
    };

    messageServiceObj.getMessageNotifications = function() {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/chat-messages.php?flag=3').then(function(response) {
                resolve(response.data);
            });
        });
    };

    messageServiceObj.getNewMessages = function() {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/chat-messages.php').then(function(response) {
                resolve(response.data);
            });
        });
    };

    messageServiceObj.sendMessage = function(data) {
        return $q(function(resolve, reject) {
            $http.post('/chat/backend/requests/chat-messages.php', data).then(function(response) {
                resolve(response.data);
            });
        });
    };

    messageServiceObj.markAllMessageNotificationsAsRead = function() {
        return $q(function(resolve, reject) {
            $http.put('/chat/backend/requests/chat-messages.php?flag=1').then(function(response) {
                resolve(response.data);
            });
        });
    };

    messageServiceObj.getLastCurrentUserMessages = function(offset) {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/chat-messages.php?flag=2&offset=' + offset).then(function(response) {
                resolve(response.data);
            });
        });
    };

    return messageServiceObj;
}]);