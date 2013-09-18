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

      $scope.selectOption = function(option) {
        $scope.modelValue = option.name;
        console.log($scope.modelValue);
      };

      $scope.options = [
        {name: 'Some field', description: 'And some longer description', id: 1},
        {name: 'Some other', description: 'And some longer description', id: 2},
        {name: 'Some another', description: 'And some longer description', id: 3},
      ];

      $scope.optionsVisible = false;
      $scope.toggleVisible = function(){
        $scope.optionsVisible = !$scope.optionsVisible;
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

      var current = element.find('.current');
      var hidden = element.find('.hidden');
      var options = element.find('.options');

      //show and calculate width, then hide
      scope.toggleVisible();
      $timeout(function(){
        var width = Math.ceil(options.width());
        current.width(width);
        options.width(width);
        scope.toggleVisible();
      }, 200);

      hidden.on('focus', function(){
        scope.optionsVisible = true;
        scope.$apply();
      });

      hidden.on('blur', function(){
        $timeout(function(){
          scope.optionsVisible = false;
          scope.$apply();
        }, 100);
      });

      current.on('click', function(){
        hidden.trigger('focus');
      });


      /*
       Watch and update NgModel
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
        console.log('Detected model value update');
        ngModel.$setViewValue(scope.modelValue);
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
