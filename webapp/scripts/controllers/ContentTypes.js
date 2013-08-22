'use strict';
var app = angular.module('shiftContentApp');
app.controller('ContentTypes', function ($scope, types) {

  $scope.types = types;

  //new type
  $scope.newType = {
    name: null,
    description: null
  };

  //new type form
  $scope.newTypeForm = {};

  //new type form controls
  $scope.formVisible = false;

  $scope.showForm = function() {
    $scope.formVisible = true;
  };

  $scope.hideForm = function() {
    $scope.formVisible = false;
    $scope.rollbackData();
    $scope.newTypeForm.$setPristine();
  };

  $scope.rollbackData = function(){
    $scope.newType.name = null;
    $scope.newType.description = null;
  };

  $scope.createType = function(){
    console.info('Posting type to backend');
    console.info($scope.newType);
  };




});
