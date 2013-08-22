'use strict';
var app = angular.module('shiftContentApp');
app.controller('ContentTypes', function ($scope, types) {

  $scope.types = types;

  $scope.errors1 = [];
  $scope.errors2 = [];

  $scope.injectErrors = function(){
    $scope.errors1.push({'message': 'This is an error message'});
    $scope.errors2.push({message: 'Here goes another one'});
  };

  //new type
  $scope.newType = {
    name: null,
    description: null
  };

  //new type form controls
  $scope.formVisible = false;

  $scope.showForm = function() {
    $scope.formVisible = true;
  };

  $scope.hideForm = function() {
    $scope.formVisible = false;
    $scope.rollbackData();
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
