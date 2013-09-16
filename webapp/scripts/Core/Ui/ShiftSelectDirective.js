'use strict';
var app = angular.module('shiftContentApp');

/**
 * ShiftSelect directive
 * Replaces standard select for backend forms.
 */
app.directive('shiftSelect', function () {
  return {
    restrict: 'EA',
    scope: {},
    templateUrl: '/modules/shift-content-new/views/directives/form-select.html',
    link: function(scope, element, attrs) {

    }
  };
});
