'use strict';
var app = angular.module('shiftContentApp');

/**
 * NavigationDirective
 * Responsible for displaying navigation from content provided by navigation
 * service.
 */
app.directive('shiftNavigation', function (NavigationService) {
  return {
    scope: {}, //isolate directive scope
    restrict: 'EA',
    templateUrl: '/modules/shift-content-new/views/directives/navigation.html',
    controller: function($scope, $location){

      var navigation = NavigationService;

      var setActiveItem = function(){
        var currentRoute = $location.path().substring(1);
        for(var section in navigation) {
          for(var i in navigation[section].items) {
            if(currentRoute === navigation[section].items[i].href) {
              navigation[section].items[i].active = true;
            } else {
              navigation[section].items[i].active = false;
            }
          }
        }

      };

      setActiveItem();
      $scope.$on('$routeChangeStart', function() {
        setActiveItem();
      });


      $scope.navigation = navigation;
    }
  };
});