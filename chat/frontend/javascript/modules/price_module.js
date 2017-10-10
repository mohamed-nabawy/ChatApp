var priceApp = angular.module('price', []);

priceApp.directive("numberCheck", function() {

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
      var regExp = /^\d{0,9}(\.\d{0,2}){0,1}$/; // regular expression for matching floating point numbers only
      ctrl.$parsers.unshift(function(val) {
        if (checkType(val,regExp) ) {
          ctrl.$setValidity('numberCheck', true);
          ctrl.$setValidity('numberEmpty', true);
          return val;
        }
        else {
          if (isEmpty(val) ) {
            ctrl.$setValidity('numberEmpty', false);
            ctrl.$setValidity('numberCheck', true);
            return undefined;
          }
          else {
            ctrl.$setValidity('numberCheck', false);
            ctrl.$setValidity('numberEmpty', true);
            return undefined;
          }
        }
      });
      ctrl.$formatters.unshift(function(val) {
        if (checkType(val,regExp) ) {
          ctrl.$setValidity('numberEmpty', true);
          ctrl.$setValidity('numberCheck', true);
        }
        else {
          if (!isEmpty(val) ) {
            ctrl.$setValidity('numberCheck', false);
            ctrl.$setValidity('numberEmpty', true);
          }
          else {
            ctrl.$setValidity('numberEmpty', false);
            ctrl.$setValidity('numberCheck', true);
          }
        }
        return val;
      });
    }
  }
});