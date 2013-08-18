'use strict';
var app = angular.module('shiftContentApp');

/**
 * Superhero directive
 * This is an example of angular directive having a controller, that
 * allows a directive to expose an api and communicate with other directives.
 */
app.directive('shiftNavigation', function (NavigationService) {
  return {
    scope: {}, //isolate directive scope
    restrict: 'EA',
    templateUrl: '/modules/shift-content-new/views/parts/navigation.html',
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