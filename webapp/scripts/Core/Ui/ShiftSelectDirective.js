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

      $scope.selectNullValue = function(){
        $timeout(function(){
          $scope.modelValue = $scope.options[0].name;
        });
      };

      $scope.options = [
        {name: 'Text field', description: 'And some longer description', id: 1},
        {name: 'File field', description: 'And some longer description', id: 2},
        {name: 'Media album field', description: 'And some longer description', id: 3}
      ];

      $scope.optionsVisible = false;
      $scope.toggleVisible = function(){
        $scope.optionsVisible = !$scope.optionsVisible;
      };



    },
    /*
     * Work with NgModel here to update bindings on the parent scope
     * and provide element validation with standard form validation means.
     *
     * We can operate on NgModel directly rather than having a hidden
     * element.
     *
     * Yet hidden element is useful for binding to its focus and blur events
     * to show/hide options.
     */
    link: function(scope, element, attrs, ngModel) {
      if(!ngModel) return; // do nothing if no ng-model

      var current = element.find('.current');
      var hidden = element.find('.hidden');
      var options = element.find('.options');

      //push null value
      $timeout(function(){

        var nullOption = element.find('option');
        if(nullOption.length >= 1) {
          var el = angular.element(nullOption[0]);
          var option = {
            name: el.html(),
            value: null //hardcoded option with null value
          };
          scope.options.unshift(option);
          nullOption.each(function(i, el){
            angular.element(el).remove();
          });
        }
      });


      //show on focus
      hidden.on('focus', function(){
        scope.optionsVisible = true;
        scope.$apply();
      });

      //hide on blur
      hidden.on('blur', function(){
        $timeout(function(){
          scope.optionsVisible = false;
          scope.$apply();
        }, 100);
      });

      //focus on click
      current.on('click', function(){
        hidden.trigger('focus');
      });


      /*
       * Watch and update NgModel
       */

      // Specify how UI should be updated
      ngModel.$render = function() {
        console.log('Setup directive and render');
        var value = ngModel.$viewValue || '';
        if(!value) {
          scope.selectNullValue();
        } else {
          scope.modelValue = value;
        }
      };

      //watch for model changes
      scope.$watch('modelValue', function(newValue, oldValue) {
        console.log('Detected model value update');
        ngModel.$setViewValue(scope.modelValue);
       });



    }
  };
});
