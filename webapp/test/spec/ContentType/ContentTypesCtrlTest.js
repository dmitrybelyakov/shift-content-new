'use strict';

describe('ContentTypesCtrl: ', function () {

  var scope;
  var controller;

  var deps; //controller dependencies
  var promise; //promise stub
  var types;
  var notifications;

  //init app
  beforeEach(module('shiftContentApp'));

  //inject dependencies
  beforeEach(inject(function($rootScope, $controller){
    scope = $rootScope.$new();
    controller = $controller;
  }));

  //create controller
  beforeEach(function(){

    //some initial types
    types = [
      {id: 123, name: 'Z goes last'},
      {id: 456, name: 'Me is in the middle'},
      {id: 789, name: 'A goes first'}
    ];

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
      types: types,
      NotificationService: notifications,
      TypeRepository: {}
    };
  });

  //IMPORTANT: reset state
  afterEach(function(){
    deps.TypeRepository = {};
    deps.NotificationService.send.reset();
    deps.NotificationService.growl.reset();
  });


  //-------------------------------------------------------------------------


  it('sorts types on init', function(){
    controller('ContentTypesCtrl', deps);
    expect(scope.types[0]).toEqual(types[2]);
    expect(scope.types[1]).toEqual(types[1]);
    expect(scope.types[2]).toEqual(types[0]);
  });

  /*
   * Delete type
   */

  it('can go to type editor screen', inject(function($location){
    controller('ContentTypesCtrl', deps);
    expect($location.path()).toBe('/');

    var id = '123';
    scope.editType(id);
    expect($location.path()).toBe('/' + id + '/');
  }));


  it('can delete type and catch 404', function(){

    var type = {name: 'delete me'};

    //mock repo
    var repo = {};
    repo.delete = jasmine.createSpy('delete type')
      .andCallFake(function(){
        var result = angular.copy(promise);
        result.catch = function(c){c({status: 404})};
        return result;
    });

    //init controller
    deps.TypeRepository = repo;
    controller('ContentTypesCtrl', deps);

    //now delete
    scope.deleteType(type);
    expect(repo.delete).toHaveBeenCalledWith(type);
    expect(notifications.send).toHaveBeenCalledWith(
      'default', 'error', 'Server error: Not found'
    );
  });


  it('can delete type and catch 500', function(){

    var type = {name: 'delete me'};

    //mock repo
    var repo = {};
    repo.delete = jasmine.createSpy('delete type')
      .andCallFake(function(){
        var result = angular.copy(promise);
        result.catch = function(c){c({status: 500})};
        result.error = function(c){c({content: 'Api exception'})};
        return result;
    });

    //init controller
    deps.TypeRepository = repo;
    controller('ContentTypesCtrl', deps);

    //now delete
    scope.deleteType(type);
    expect(repo.delete).toHaveBeenCalledWith(type);
    expect(notifications.send).toHaveBeenCalledWith(
      'default', 'error', 'Server error: Api exception'
    );
  });


  it('can delete type', function(){

    //mock repo
    var repo = {};
    repo.delete = jasmine.createSpy('delete type')
      .andCallFake(function(){
        var result = angular.copy(promise);
        result.catch = function(c){c({status: 204})};
        return result;
    });

    //init controller
    deps.TypeRepository = repo;
    controller('ContentTypesCtrl', deps);
    expect(scope.types.length).toBe(3);

    //now delete
    scope.deleteType(types[1]);
    expect(repo.delete).toHaveBeenCalledWith(types[1]);
    expect(notifications.growl).toHaveBeenCalledWith('Content type deleted');

    //deleted removed from list
    expect(scope.types[0]).toEqual(types[2]);
    expect(scope.types[1]).toEqual(types[0]);
  });


  /*
   * Add new type
   */

  it('can show new type form', function(){
    controller('ContentTypesCtrl', deps);

    expect(scope.formVisible).toBe(false);
    scope.showForm();
    expect(scope.formVisible).toBe(true);
  });

  it('can hide form and rollback data', function(){
    controller('ContentTypesCtrl', deps);

    //mock form
    var form = {shift: {}};
    form.shift.clearBackendErrors = jasmine.createSpy('clear errors');
    form.shift.clearSubmitted = jasmine.createSpy('clear sumit');
    form.$setPristine = jasmine.createSpy('set clear');
    scope.newTypeForm = form;

    scope.showForm();
    scope.newType.name = 'some name';
    scope.newType.description = 'some description';

    scope.hideForm();
    expect(scope.newType.name).toBeUndefined();
    expect(scope.newType.description).toBeUndefined();
  });

  it('cancels type creation if form invalid', function () {
    controller('ContentTypesCtrl', deps);

    //mock form
    scope.newTypeForm = {};
    scope.newTypeForm.$invalid = true;

    expect(scope.createType()).toBeUndefined();
  });

  it('can create type and handle validation errors', function(){

    //mock backend validation errors
    var backendValidationErrors = {name: ['Name errors']};

    //mock repo
    var repo = {};
    repo.create = jasmine.createSpy('create type')
      .andCallFake(function(){
        var result = angular.copy(promise);
        result.catch = function(c){c({
          status: 409,
          data: backendValidationErrors
        })};
        result.success = function(){}; //do nothing
        return result;
    });

    deps.TypeRepository = repo;
    controller('ContentTypesCtrl', deps);

    //mock form
    var form = {shift: {}};
    form.$invalid = false;
    form.shift.setBackendErrors = jasmine.createSpy('set backend errors');
    scope.newTypeForm = form;

    //now create
    scope.createType();

    //assert validation errors set
    expect(form.shift.setBackendErrors).toHaveBeenCalledWith(
      backendValidationErrors
    );

  });

  it('can create type and catch 500', function () {

    //mock repo
    var repo = {};
    repo.create = jasmine.createSpy('create type')
      .andCallFake(function(){
        var result = angular.copy(promise);
        result.catch = function(c){c({status: 500})};
        result.error = function(c){c({content: 'Api error'})};
        result.success = function(){};

        return result;
    });

    deps.TypeRepository = repo;
    controller('ContentTypesCtrl', deps);

    //now create
    scope.createType();

    //assert error set
    expect(notifications.send).toHaveBeenCalledWith(
      'default', 'error', 'Server error: Api error'
    );
  });

//
//  it('can create type', function () {
//
//  });
//
//  it('marks form progress while working', function () {
//
//  });


});
