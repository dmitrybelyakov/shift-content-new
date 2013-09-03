'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content type controller
 * Responsible for viewing and editing content type
 */
app.controller('ContentTypeCtrl', function (
  $scope,
  type,
  TypeRepository,
  NotificationService) {

  var _ = window._;
  var repository = TypeRepository;
  var notifications = NotificationService;

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
    $scope.typeFormProgress = false;
    $scope.type.name = $scope.typeMemory.name;
    $scope.type.description = $scope.typeMemory.description;
  };

  //save type and handle response
  $scope.saveType = function(){
    if($scope.editTypeForm.$invalid) {
      return;
    }

    //submit request
    $scope.typeFormProgress = true;
    var promise = repository.update($scope.type);

    //catch backend validation
    var validationErrors;
    promise.catch(function(response){
      if(response.status === 409) {
        validationErrors = response.data;
      }
    });

    //handle errors
    promise.error(function(apiException){
      $scope.typeFormProgress = false;

      if(validationErrors) {
        //server validation
        $scope.editTypeForm.shift.setBackendErrors(validationErrors);
      } else {
        //server exception
        var message = 'Server error';
        if(apiException.content) {
          message += ': '+ apiException.content;
        }
        notifications.send('typeMessages', 'error', message);
      }
    });

    //handle success
    promise.success(function(type){
      $scope.type = type;
      $scope.typeMemory = _.clone(type);
      $scope.hideTypeForm();
      notifications.growl('Saved content type');
    });


  };


  /*
   * Manage fields
   */
  $scope.fieldFormVisible = false;
  $scope.fieldFormProgress = false;




});
