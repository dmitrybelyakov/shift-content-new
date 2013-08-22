'use strict';
var app = angular.module('shiftContentApp');
app.controller('ContentTypes', function ($scope, types) {

  $scope.types = [1,2,3,4,5];//types;


  //new type form
  $scope.newTypeForm = {};

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
    $scope.newTypeForm.$setPristine();
  };

  $scope.rollbackData = function(){
    $scope.newType.name = null;
    $scope.newType.description = null;
  };

  $scope.createType = function(){

    //check validity
    var form = $scope.newTypeForm;
    if(form.$invalid) {
      return;
    }

//    console.info('Check validation state first');
//    console.info('Invalid: ' + form.$invalid);
//    console.info('Pristine: ' + form.$pristine);
//    console.info(form.$error);


    //submitting data to backend
    console.info('Submitting data to backend');




  };




});
