'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content type controller
 * Responsible for viewing and editing content type
 */
app.controller('ContentTypeCtrl', function (
  $scope,
  type,
  fieldTypes,
  TypeRepository,
  NotificationService,
  $window,
  $timeout,
  $log) {

  var _ = $window._;
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
    $scope.hideFieldForm();
  };

  //rollback data and hide form
  $scope.hideTypeForm = function(){

    //hide
    $scope.typeFormVisible = false;
    $scope.typeFormProgress = false;

    //roll back
    $scope.type.name = $scope.typeMemory.name;
    $scope.type.description = $scope.typeMemory.description;

    //clear form state
    $scope.editTypeForm.shift.clearBackendErrors();
    $scope.editTypeForm.shift.clearSubmitted();
    $scope.editTypeForm.$setPristine();
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

    //catch not found
    var notFound;
    promise.catch(function(response){
      if(response.status === 404) {
        notFound = true;
      }
    });

    //handle errors
    promise.error(function(apiException){
      $timeout(function(){
        $scope.typeFormProgress = false;
      });


      var message = 'Server error';

      //failed validation
      if(validationErrors) {
        $scope.editTypeForm.shift.setBackendErrors(validationErrors);
        return;
      }

      if(notFound) {
        message += ': Not found';
        notifications.send('typeMessages', 'error', message);
      }

      if(apiException.content) {
        message += ': '+ apiException.content;
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
  $scope.fieldTypes = fieldTypes;
  $scope.newField = {
    name: undefined,
    property: undefined,
    type: undefined
  };



  $scope.fields = [
    {name: 'Some field', description: 'And some longer description', value: 1},
    {name: 'Some field', description: 'And some longer description', value: 2},
    {name: 'Some field', description: 'And some longer description', value: 3},
    {name: 'Some field', description: 'And some longer description', value: 4},
    {name: 'Some field', description: 'And some longer description', value: 5},
    {name: 'Some field', description: 'And some longer description', value: 6},
    {name: 'Some field', description: 'And some longer description', value: 7}
  ];

  //show field form
  $scope.showFieldForm = function(){
    $scope.fieldFormVisible = true;
    $scope.hideTypeForm();
  };

  //hide field form & rollback data
  $scope.hideFieldForm = function(){
    $scope.fieldFormVisible = false;
    $scope.newField = {
      name: undefined,
      property: undefined,
      type: undefined
    };
  };

  //add field and handle response
  $scope.addField = function(){
    if($scope.addFieldForm.$invalid) {
      return;
    }

    $log.log('NOW SUBMIT REQUEST');
    $log.log($scope.newField);

  };




});
