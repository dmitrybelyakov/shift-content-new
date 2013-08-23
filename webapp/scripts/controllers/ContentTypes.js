'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content types controller
 * Responsible for displaying content types, creation and deletion of types.
 */
app.controller('ContentTypes', function (
  $scope,
  contentTypes,
  AnotherContentTypeRepository) {

  /*
   * Existing types
   */

  $scope.types = contentTypes;
  var repository = AnotherContentTypeRepository;



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
  $scope.formProgress = false;

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

    console.info('sending');
    $scope.formProgress = true;

    //submit request
    repository.create($scope.newType)
      .success(function(response){
        $scope.types.push(response);
        $scope.formProgress = false;
        $scope.hideForm();
      })
      .error(function(rejectReason){
        $scope.formProgress = false;
        $scope.hideForm();
        console.info(rejectReason);
      });


  };




});
