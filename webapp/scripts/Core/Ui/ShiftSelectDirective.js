'use strict';
var app = angular.module('shiftContentApp');

/**
 * ShiftSelect directive
 * Replaces standard select for backend forms. Uses NgModel to work with bound
 * values and provide integration with form validation facilities.
 */
app.directive('shiftSelect', function ($document, $timeout) {
  return {
    restrict: 'EA',
    templateUrl: '/modules/shift-content-new/views/directives/form-select.html',
    replace: true,
    transclude: true,
    require: '?ngModel',
    scope: true, //ngModel unable to work in isolate scope
    controller: function($scope){

    /*
      Scope controls the behavior of the select replacement. All working
      with actual values and binding is done in the linking function.
     */

      $scope.modelValue = undefined;
      //this is child scope (not the parent controller scope)
      $scope.logValue = function(){
        console.log('now log');
        console.log($scope.modelValue);
      };


    },
    link: function(scope, element, attrs, ngModel) {
      if(!ngModel) return; // do nothing if no ng-model

      /*
         Work with NgModel here to update bindings on the parent scope
         and provide element validation with standard form validation means.

         We can operate on NgModel directly rather than having a hidden
         element.
       */

      //get the editable
      var editable = element.find('.editable');

      // Specify how UI should be updated
      ngModel.$render = function() {
        var value = ngModel.$viewValue || '';
        editable.html(value);
        scope.modelValue = value;
      };

      //watch for model changes
      scope.$watch('modelValue', function(newValue, oldValue) {
           if (newValue) {
             //watch for controller changes here and update NgModel
             console.log('I see dead people');
           }

       });

      // Listen for change events to enable binding
      editable.on('blur keyup change', function() {
        console.log('changed');
        scope.$apply(read);
        scope.modelValue = ngModel.$viewValue; //reflect in local scope
        scope.$apply('logValue()');
      });


      function read(){
        var html = editable.html();
        ngModel.$setViewValue(html);
      };





    }
  };
});
