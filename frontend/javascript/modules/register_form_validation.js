var registerFormApp = angular.module('registerFormValidation', ['user-service']);

registerFormApp.directive('check', ['$http', 'userService', function($http, userService) {
  function checkType(val, regExp) {
    if ( regExp.test(val) ) {
      return true;
    }
    else {
      return false;
    }
  }

  return {
    restrict: 'A',
    require: 'ngModel',
    link: function(scope, elem, attr, ctrl) {
      var regExpPass = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/;
      var regExpPhone = /^01\d{9}$/;
      var regExpBirth = /^\d{4}-[0-9]([0-9])?-\d{1,2}$/;

      ctrl.$parsers.unshift(function(val) {
        if (attr.name == 'email') {
          if (val != undefined) {
            var x = {
              Email: val
            };

            userService.checkExistingEmail(x).then(function(data) {
              //console.log(response);
              if (data == "1") {
                ctrl.$setValidity('emailExisted', false);
                ctrl.$setValidity('emailEmpty', true);

                return undefined;
              }
              else if (val == '') {
                ctrl.$setValidity('emailEmpty', false);
                ctrl.$setValidity('emailExisted', true);

                return undefined;
              }
              else {
                ctrl.$setValidity('emailExisted', true);
                ctrl.$setValidity('emailEmpty', true);

                return val;            
              }
            });

            if (val == '') {
              ctrl.$setValidity('emailEmpty', false);
            }
            else {
              ctrl.$setValidity('emailEmpty', true);
            }

            return val; // important because of the asynchronous request
          }
          else {
            ctrl.$setValidity('emailEmpty', false);

            return undefined;
          }
        }
        else if (attr.name == 'password') {
          if (val != undefined) {
            if ( checkType(val, regExpPass) ) {
              ctrl.$setValidity('checkPassword', true);
              ctrl.$setValidity('passEmpty', true);
              
              return val;
            }
            else if (val == '') {
              ctrl.$setValidity('passEmpty', false);
              ctrl.$setValidity('checkPassword', true);
              
              return undefined;
            }
            else {
              ctrl.$setValidity('checkPassword', false);
              ctrl.$setValidity('passEmpty', true);

              return undefined;  
            }
          }
          else {
            ctrl.$setValidity('passEmpty', false);
            ctrl.$setValidity('checkPassword', true);

            return undefined;
          }
        }
        else if (attr.name == 'confirmPassword') {
          if (val != undefined) {
            if ( checkType(val, regExpPass) ) {
              ctrl.$setValidity('checkConfirmPassword', true);
              ctrl.$setValidity('confirmPassEmpty', true);
              
              return val;
            }
            else if (val == '') {
              ctrl.$setValidity('confirmPassEmpty', false);
              ctrl.$setValidity('checkConfirmPassword', true);
              
              return undefined;
            }
            else {
              ctrl.$setValidity('checkConfirmPassword', false);
              ctrl.$setValidity('confirmPassEmpty', true);

              return undefined;  
            }
          }
          else {
            ctrl.$setValidity('confirmPassEmpty', false);
            ctrl.$setValidity('checkConfirmPassword', true);

            return undefined;
          }
        }
        else if (attr.name == 'phone') {
          if (val != undefined) {
            if ( checkType(val, regExpPhone) ) {
              ctrl.$setValidity('checkPhoneNumber', true);
              ctrl.$setValidity('phoneEmpty', true);
              
              return val;
            }
            else if (val == '') {
              ctrl.$setValidity('phoneEmpty', false);
              ctrl.$setValidity('checkPhoneNumber', true);
              
              return undefined;
            }
            else {
              ctrl.$setValidity('checkPhoneNumber', false);
              ctrl.$setValidity('phoneEmpty', true);

              return undefined;
            }
          }
          else {
            ctrl.$setValidity('phoneEmpty', false);
            ctrl.$setValidity('checkPhoneNumber', true);

            return undefined;
          }
        }
        else if (attr.name == 'DOB') {
          if (val != undefined) {
            if ( checkType(val, regExpBirth) ) {
              ctrl.$setValidity('checkBirth', true);
              ctrl.$setValidity('birthEmpty', true);
              
              return val;
            }
            else if (val == '') {
              ctrl.$setValidity('checkBirth', true);
              ctrl.$setValidity('birthEmpty', false);
              
              return undefined;
            }
            else {
              ctrl.$setValidity('checkBirth', false);
              ctrl.$setValidity('birthEmpty', true);

              return undefined;
            }
          }
          else {
            ctrl.$setValidity('birthEmpty', false);

            return undefined;
          }
        }
      });
      ctrl.$formatters.unshift(function(val) {
        if (attr.name == 'email') {
          if (val != undefined) {
            var x = {
              Email: val
            };

            userService.checkExistingEmail(x).then(function(data) {
              if (data == "1") {
                ctrl.$setValidity('emailExisted', false);
                ctrl.$setValidity('emailEmpty', true);
              }
              else if (val == '') {
                ctrl.$setValidity('emailEmpty', false);                  
              }
              else {
                ctrl.$setValidity('emailExisted', true);
                ctrl.$setValidity('emailEmpty', true);             
              }
            });
          }
          else {
            ctrl.$setValidity('emailEmpty', false);
          }
        }
        else if (attr.name == 'password') {
          if (val != undefined) {
            if ( checkType(val, regExpPass) ) {
              ctrl.$setValidity('checkPassword', true);
              ctrl.$setValidity('passEmpty', false);
            }
            else if (val == '') {
              ctrl.$setValidity('passEmpty', false);
              ctrl.$setValidity('checkPassword', true);          
            }
            else {
              ctrl.$setValidity('checkPassword', false);
              ctrl.$setValidity('passEmpty', true);
            }
          }
          else {
            ctrl.$setValidity('passEmpty', false);
            ctrl.$setValidity('checkPassword', true);
          }
        }
        else if (attr.name == 'confirmPassword') {
          if (val != undefined) {
            if ( checkType(val, regExpPass) ) {
              ctrl.$setValidity('checkConfirmPassword', true);
              ctrl.$setValidity('confirmPassEmpty', true);
            }
            else if (val == '') {
              ctrl.$setValidity('confirmPassEmpty', false);
              ctrl.$setValidity('checkConfirmPassword', true);             
            }
            else {
              ctrl.$setValidity('checkConfirmPassword', false);
              ctrl.$setValidity('confirmPassEmpty', true);
            }
          }
          else {
            ctrl.$setValidity('confirmPassEmpty', false);
            ctrl.$setValidity('checkConfirmPassword', true);
          }
        }
        else if (attr.name == 'phone') {
          if (val != undefined) {
            if ( checkType(val, regExpPhone) ) {
              ctrl.$setValidity('checkPhoneNumber', true);
              ctrl.$setValidity('phoneEmpty', true);
            }
            else if (val == '') {
              ctrl.$setValidity('phoneEmpty', false);
              ctrl.$setValidity('checkPhoneNumber', true);
            }
            else {
              ctrl.$setValidity('checkPhoneNumber', false);
              ctrl.$setValidity('phoneEmpty', true);
            }
          }
          else {
            ctrl.$setValidity('phoneEmpty', false);
            ctrl.$setValidity('checkPhoneNumber', true);
          }
        }
        else if (attr.name == 'DOB') {
          if (val != undefined) {
            if ( checkType(val, regExpBirth) ) {
              ctrl.$setValidity('checkBirth', true);
              ctrl.$setValidity('birthEmpty', true);
            }
            else if (val == '') {
              ctrl.$setValidity('birthEmpty', false);
              ctrl.$setValidity('checkBirth', true);
            }
            else {
              ctrl.$setValidity('checkBirth', false);
              ctrl.$setValidity('birthEmpty', true);
            }
          }
          else {
            ctrl.$setValidity('birthEmpty', false);
            ctrl.$setValidity('checkBirth', true);
          }
        }

        return val;
      });
    }
  }
}]);