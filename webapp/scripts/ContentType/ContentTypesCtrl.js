'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content types controller
 * Responsible for displaying content types, creation and deletion of types.
 */
app.controller('ContentTypesCtrl', function (
  $scope,
  $location,
  $window,
  types,
  TypeRepository,
  NotificationService,
  $timeout) {

  var _ = $window._;
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
      else if(apiException.content) {
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
    name: '',
    description: ''
  };

  $scope.showForm = function() {
    $scope.formVisible = true;
  };

  $scope.hideForm = function() {

    //hide
    $scope.formVisible = false;
    $scope.formProgress = false;

    //rollback
    $scope.newType.name = '';
    $scope.newType.description = '';

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

    var promise;
    promise = repository.create($scope.newType);

    //catch backend validation
    var validationErrors;
    promise.catch(function(response){
      if(response.status === 409) {
        validationErrors = response.data;
      }
    });

    //catch not found
    var notFound = false;
    promise.catch(function(response){
      if(response.status === 404) {
        notFound = true;
      }
    });

    //handle errors
    promise.error(function(apiException){
      $timeout(function(){ //timeout to test
        $scope.formProgress = false;
      }, 0);

      //server validation
      if(validationErrors) {
        $scope.newTypeForm.shift.setBackendErrors(validationErrors);
        return;
      }

      //errors
      var message = 'Server error';
      if(notFound) {
        message += ': Not found';
      }
      else if(apiException.content) {
        message += ': '+ apiException.content;
      }
      notifications.send('default', 'error', message);

    });

    //handle success
    promise.success(function(response){
      $scope.types.push(response);
      $scope.types = _.sortBy($scope.types, function(type){
        return type.name;
      });

      $timeout(function(){ //timeout to test
        $scope.hideForm();
      }, 0);

      notifications.growl('Added content type');
    });





  };




});
