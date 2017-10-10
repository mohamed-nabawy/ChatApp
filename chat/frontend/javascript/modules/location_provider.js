var location_providerApp = angular.module('location_provider', []);

location_providerApp.config(['$locationProvider',function($locationProvider) {

  $locationProvider.html5Mode({
    enabled: true,
    requireBase: false
  });

}]);