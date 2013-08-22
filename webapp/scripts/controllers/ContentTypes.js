'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content types controller
 * Responsible for displaying content types, creation and deletion of types.
 */
app.controller('ContentTypes', function (
  $scope,
  contentTypes,
  ContentTypeRepository) {

  /*
   * Existing types
   */

  var repository = ContentTypeRepository;

  //types
  $scope.types = [1,2,3,4,5];


  /*
   * New type form
   */

  //new type form
  $scope.newTypeForm = {};

  //new type
  $scope.newType = {
    name: undefined,
    description: undefined
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
    $scope.newType.name = undefined;
    $scope.newType.description = undefined;
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
//    console.info('Submitting data to backend');
//    console.info($scope.newType);
    var result = repository.save($scope.newType);

    result.$promise.then(function(){
      console.info(result);
    });






  };




});
