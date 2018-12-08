var userServiceApp = angular.module('user-service', []);

userServiceApp.factory('userService', ['$http', '$q', function($http, $q) {
    var userServiceObj = {};

    userServiceObj.getUserInSession = function() {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/users.php?flag=3').then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.getChats = function() {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/users.php?flag=1').then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.deleteUserFromChatsInSession = function(userId) {
        return $q(function(resolve, reject) {
            $http.delete('/chat/backend/requests/users.php?id=' + userId).then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.addChatUser = function(data) {
        return $q(function(resolve, reject) {
            $http.put('/chat/backend/requests/users.php?flag=1', data).then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.updateOpenStateOfChat = function(secondUserId, f) {
        return $q(function(resolve, reject) {
            $http.put('/chat/backend/requests/users.php?chatId=' + secondUserId + '&open=' + f).then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.openChatInSession = function(secondUserId) {
        return $q(function(resolve, reject) {
            $http.put('/chat/backend/requests/users.php?chatId=' + secondUserId + '&open=1').then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.updateUserImage = function(data) {
        return $q(function(resolve, reject) {
            $http.post('/chat/backend/requests/users.php?update=1', data).then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.deleteUserImage = function() {
        return $q(function(resolve, reject) {
            $http.delete('/chat/backend/requests/users.php?f=1').then(function(response) {
                resolve(response.data);
            });
        });
    };

    userServiceObj.getMyClassMatesAndTeachers = function() {
        return $q(function(resolve, reject) {
            $http.get('/chat/backend/requests/users.php').then(function(response) {
                resolve(response.data);
            });
        });
    };

    return userServiceObj;
}]);