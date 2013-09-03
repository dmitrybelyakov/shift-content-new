'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content type controller
 * Responsible for viewing and editing content type
 */
app.controller('ContentTypeCtrl', function ($scope, type) {

  $scope.type = type;


});
