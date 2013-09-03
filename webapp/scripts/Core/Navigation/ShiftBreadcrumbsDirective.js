'use strict';
var app = angular.module('shiftContentApp');

/**
 * Breadcrumbs
 * Responsible for displaying breadcrumbs from content provided by
 * navigation service.
 */
app.directive('shiftBreadcrumbs', function () {

  //directive definition
  return {
    scope: {}, //isolate directive scope
    restrict: 'EA',
    templateUrl: '/modules/shift-content-new/views/directives/breadcrumbs.html'
  };
});