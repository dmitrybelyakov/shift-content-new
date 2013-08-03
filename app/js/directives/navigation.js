'use strict';
var app = angular.module('shiftContentApp');

/**
 * Superhero directive
 * This is an example of angular directive having a controller, that
 * allows a directive to expose an api and communicate with other directives.
 */
app.directive('shiftNavigation', function () {
  return {
    scope: {}, //isolate directive scope
    restrict: 'EA',
    templateUrl: '/modules/shift-content-new/views/navigation.html'
  };
});