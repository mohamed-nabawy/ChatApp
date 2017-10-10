var phone_numberApp = angular.module('phone_number', []);

phone_numberApp.directive('checkPhoneNumber', function() {

  function checkType(val, regExp) {
    if (regExp.test(val) && !isEmpty(val) ) {
      return true;
    }
    else {
      return false;
    }
  }

  function isEmpty(val) {
    if (val == "") {
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
      var regExp = /^\d{0,13}$/;
      ctrl.$parsers.unshift(function(val) {
        if (checkType(val, regExp) ) {
          ctrl.$setValidity('checkPhoneNumber', true);
          ctrl.$setValidity('numberEmpty', true);
          return val;
        }
        else {
          if (isEmpty(val) ) {
            ctrl.$setValidity('numberEmpty', false);
            ctrl.$setValidity('checkPhoneNumber', true);
            return undefined;
          }
          else {
            ctrl.$setValidity('checkPhoneNumber', false);
            ctrl.$setValidity('numberEmpty', true);
            return undefined;
          }
        }
      });
      ctrl.$formatters.unshift(function(val) {
        if (checkType(val,regExp) ) {
          ctrl.$setValidity('numberEmpty', true);
          ctrl.$setValidity('checkPhoneNumber', true);
        }
        else {
          if (!isEmpty(val) ) {
            ctrl.$setValidity('checkPhoneNumber', false);
            ctrl.$setValidity('numberEmpty', true);
          }
          else {
            ctrl.$setValidity('numberEmpty', false);
            ctrl.$setValidity('checkPhoneNumber', true);
          }
        }
        return val;
      });
    }
  }
})