'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content type controller
 * Responsible for viewing and editing content type
 */
app.controller('ContentTypeCtrl', function ($scope, type) {

  var _ = window._;

  /*
   * Edit content type
   */
  $scope.type = type;
  $scope.typeMemory = _.clone(type);
  $scope.typeFormVisible = false;
  $scope.typeFormProgress = false;

  //show edit type form
  $scope.showTypeForm = function(){
    $scope.typeFormVisible = true;
  };

  //rollback data and hide form
  $scope.hideTypeForm = function(){
    $scope.typeFormVisible = false;

    $scope.type.name = $scope.typeMemory.name;
    $scope.type.description = $scope.typeMemory.description;
  };


  /*
   * Manage fields
   */
  $scope.fieldFormVisible = false;
  $scope.fieldFormProgress = false;




});
