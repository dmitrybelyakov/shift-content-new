'use strict';

describe('ContentTypeCtrl: ', function () {

  var scope;
  var controller;
  var timeout;

  var deps; //controller dependencies
  var promise; //promise stub
  var type;
  var notifications;

  //init app
  beforeEach(module('shiftContentApp'));

  //inject dependencies
  beforeEach(inject(function($rootScope, $controller, $timeout){
    scope = $rootScope.$new();
    controller = $controller;
    timeout = $timeout;
  }));

  //create controller
  beforeEach(function(){

    //edited type
    type = {
      id: 123,
      name: 'Me is type under test',
      description: 'I have description as well'
    };

    //mock notification service
    notifications = {};
    notifications.send = jasmine.createSpy('notification');
    notifications.growl = jasmine.createSpy('growl');

    //create promise stub
    promise = {
      catch: function(c){c({status: 200})},
      error: function(c){c({})},
      success: function(c){c()}
    };

    //set dependencies
    deps = {
      $scope: scope,
      type: type,
      NotificationService: notifications,
      TypeRepository: {},
      fieldTypes: []
    };
  });


  //-------------------------------------------------------------------------

  /*
   * Edit content type
   */
  describe('Type editor', function(){

    it('memorizes initial content type', function () {
      controller('ContentTypeCtrl', deps);
      expect(scope.typeMemory.id).toEqual(type.id);
      expect(scope.typeMemory.name).toEqual(type.name);
      expect(scope.typeMemory.description).toEqual(type.description);
    });

    it('can show type form', function () {
      controller('ContentTypeCtrl', deps);
      expect(scope.typeFormVisible).toBe(false);
      scope.showTypeForm();
      expect(scope.typeFormVisible).toBe(true);
    });

    it('can hide form and rollback data', function () {
      controller('ContentTypeCtrl', deps);
      scope.type.name = 'Changed';
      scope.type.description = 'Changed';

      //mock form
      var form = {shift: {}};
      form.shift.clearBackendErrors = jasmine.createSpy('clear errors');
      form.shift.clearSubmitted = jasmine.createSpy('clear sumit');
      form.$setPristine = jasmine.createSpy('set clear');
      scope.editTypeForm = form;

      scope.showTypeForm();
      expect(scope.typeFormVisible).toBe(true);

      scope.hideTypeForm();
      expect(scope.typeFormVisible).toBe(false);

      //assert rolled back
      expect(scope.type.name).toEqual(type.name);
      expect(scope.type.description).toEqual(type.description);

      //assert rolled back
      expect(form.$setPristine).toHaveBeenCalled();
      expect(form.shift.clearSubmitted).toHaveBeenCalled();
      expect(form.shift.clearBackendErrors).toHaveBeenCalled();
    });

    it('cancel update if form invalid on frontend', function () {
      controller('ContentTypeCtrl', deps);
      scope.editTypeForm = {};
      scope.editTypeForm.$invalid = true;
      expect(scope.saveType()).toBeUndefined();
    });

    it('can update type and handle validation errors', function () {

      var validationErrors = {
        name: 'me is error'
      };

      //mock repo
      var repo = {};
      repo.update = jasmine.createSpy('delete type')
        .andCallFake(function(){
          var result = angular.copy(promise);
          result.catch = function(c){c({
            status: 409,
            data: validationErrors
          })};
          result.success = function(){};
          return result;
      });

      //mock form
      var form = {shift: {}};
      form.shift.setBackendErrors = jasmine.createSpy('errors');
      scope.editTypeForm = form;

      deps.TypeRepository = repo;
      controller('ContentTypeCtrl', deps);

      //now save
      scope.saveType(type);

      //assert errors set to form
      expect(form.shift.setBackendErrors).toHaveBeenCalledWith(validationErrors);

    });

    it('can update type and handle 404', function () {

      //mock repo
      var repo = {};
      repo.update = jasmine.createSpy('delete type')
        .andCallFake(function(){
          var result = angular.copy(promise);
          result.catch = function(c){c({status: 404})};
          result.success = function(){};
          return result;
      });

      //mock form
      scope.editTypeForm = {};

      deps.TypeRepository = repo;
      controller('ContentTypeCtrl', deps);

      //now save
      scope.saveType(type);
      expect(notifications.send).toHaveBeenCalledWith(
        'typeMessages', 'error', 'Server error: Not found'
      );

    });

    it('can update type and handle 500', function () {
      //mock repo
      var repo = {};
      repo.update = jasmine.createSpy('delete type')
        .andCallFake(function(){
          var result = angular.copy(promise);
          result.catch = function(c){c({status: 500})};
          result.error = function(c){c({content: 'Api error'})};
          result.success = function(){};
          return result;
      });

      //mock form
      scope.editTypeForm = {};

      deps.TypeRepository = repo;
      controller('ContentTypeCtrl', deps);

      //now save
      scope.saveType(type);
      expect(notifications.send).toHaveBeenCalledWith(
        'typeMessages', 'error', 'Server error: Api error'
      );
    });

    it('can update type', function () {

      var saveResult = angular.copy(type);
      saveResult.name = 'Updated';
      saveResult.description = 'Updated';

      //mock repo
      var repo = {};
      repo.update = jasmine.createSpy('delete type')
        .andCallFake(function(){
          var result = angular.copy(promise);
          result.success = function(c){c(saveResult);}; //returns saved type
          return result;
      });

      deps.TypeRepository = repo;
      controller('ContentTypeCtrl', deps);

      //mock form
      scope.editTypeForm = {shift: {}};
      scope.hideTypeForm = jasmine.createSpy('close form');

      //update type
      scope.showTypeForm();
      scope.type = saveResult;

      //now save
      scope.saveType();

      //assert saved and growled
      expect(repo.update).toHaveBeenCalledWith(scope.type);
      expect(notifications.growl).toHaveBeenCalledWith('Saved content type');

      //assert new data memorized
      expect(scope.typeMemory.name).toBe('Updated');
      expect(scope.typeMemory.description).toBe('Updated');

    });

    it('sets form progress while working', function () {
      //mock repo
      var repo = {};
      repo.update = jasmine.createSpy('create type')
        .andCallFake(function(){
          var result = angular.copy(promise);
          result.catch = function(c){c({status: 500})};
          result.success = function(){};
          return result;
      });

      deps.TypeRepository = repo;
      controller('ContentTypeCtrl', deps);

      scope.editTypeForm = {};
      expect(scope.typeFormProgress).toBe(false); //initially active

      scope.saveType();
      expect(scope.typeFormProgress).toBe(true); //progress marked

      timeout(function(){
        expect(scope.typeFormProgress).toBe(false); //back to active
      },0);
    });

  }); //type editor



  /*
   * Manage type fields
   */
  describe('Fields manager', function(){

  });


});
