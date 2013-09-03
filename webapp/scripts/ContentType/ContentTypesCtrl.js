'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content types controller
 * Responsible for displaying content types, creation and deletion of types.
 */
app.controller('ContentTypesCtrl', function (
  $scope,
  $location,
  types,
  TypeRepository,
  NotificationService) {

  var _ = window._;
  var repository = TypeRepository;
  var notifications = NotificationService;

  /*
   * Existing types
   */

  $scope.types = _.sortBy(types, function(type){
    return type.name;
  });

  //proceed to type editing
  $scope.editType = function(id){
    $location.path($location.path() + id + '/');
  };

  //delete type
  $scope.deleteType = function(type){
    var promise = repository.delete(type);

    //catch not found
    var notFound = false;
    promise.catch(function(response){
      if(response.status === 404) {
        notFound = true;
      }
    });

    //handle errors
    promise.error(function(apiException){
      var message = 'Server error';
      if(notFound) {
        message += ': Not found';
      }
      if(apiException.content) {
        message += ': '+ apiException.content;
      }
      notifications.send('default', 'error', message);
    });

    //handle success
    promise.success(function(){
      $scope.types = _.reject($scope.types, function(iteration){
        return (iteration.id === type.id);
      });
      notifications.growl('Content type deleted');
    });
  };



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
    promise.error(function(apiException){
      $scope.formProgress = false;

      if(validationErrors) {
        //server validation
        $scope.newTypeForm.shift.setBackendErrors(validationErrors);
      } else {
        //server exception
        var message = 'Server error';
        if(apiException.content) {
          message += ': '+ apiException.content;
        }
        notifications.send('default', 'error', message);
      }
    });

    //handle success
    promise.success(function(response){
      $scope.types.push(response);
      $scope.types = _.sortBy($scope.types, function(type){
        return type.name;
      });

      $scope.hideForm();
      notifications.growl('Added content type');
    });


  };




});
