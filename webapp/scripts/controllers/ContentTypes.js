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

  var _ = window._;
  var repository = AnotherContentTypeRepository;


  /*
   * Existing types
   */

  $scope.types = _.sortBy(contentTypes, function(type){
    return type.name;
  });



  /*
   * New type
   */

  //form
  $scope.newTypeForm = {};
  $scope.formVisible = false;
  $scope.formProgress = false;

  //type
  $scope.newType = {
    name: undefined,
    description: undefined
  };

  $scope.showForm = function() {
    $scope.formVisible = true;
  };

  $scope.hideForm = function() {

    //hide
    $scope.formVisible = false;
    $scope.formProgress = false;

    //rollback
    $scope.newType.name = undefined;
    $scope.newType.description = undefined;

    //set clean
    $scope.newTypeForm.shift.clearBackendErrors();
    $scope.newTypeForm.shift.clearSubmitted();
    $scope.newTypeForm.$setPristine();
  };


  //create type
  $scope.createType = function(){

    if($scope.newTypeForm.$invalid) {
      return;
    }

    //submit request
    $scope.formProgress = true;
    var promise = repository.create($scope.newType);

    //catch backend validation
    var validationErrors;
    promise.catch(function(response){
      if(response.status === 409) {
        validationErrors = response.data;
      }
    });

    //handle errors
    promise.error(function(){
      $scope.formProgress = false;
      if(validationErrors) {
        $scope.newTypeForm.shift.setBackendErrors(validationErrors);
      } else {

        //handle server error
      }


    });

    //handle success
    promise.success(function(response){
      $scope.types.push(response);
      $scope.types = _.sortBy($scope.types, function(type){
        return type.name;
      });

      $scope.hideForm();
    });


  };




});
