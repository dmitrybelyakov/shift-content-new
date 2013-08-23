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

  //remove type
  $scope.deleteType = function(xx){
    console.info('Now delete type');
    console.info(xx);
  };



  /*
   * New type form
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

    //rollback
    $scope.newType.name = undefined;
    $scope.newType.description = undefined;

    //and hide
    $scope.formVisible = false;
    $scope.formProgress = false;
    $scope.newTypeForm.$setPristine();
  };

  //create type
  $scope.createType = function(){

    //check validity
    var form = $scope.newTypeForm;
    if(form.$invalid) {
      return;
    }

    $scope.formProgress = true;

    //submit request
    var promise = repository.create($scope.newType);

    //catch backend validation
    var validationErrors;
    promise.catch(function(response){
      if(response.status === 409) {
        validationErrors = response.data;
      }
    });

    //handle errors
    promise.error(function(error){

      if(validationErrors) {
        console.info('Got validation errors:');
        console.info(validationErrors);
      } else {
        console.info('Got server error:');
        console.info(error);
      }


    });

    //handle success
    promise.success(function(response){
      $scope.types.push(response);
      $scope.hideForm();
    });


  };




});
