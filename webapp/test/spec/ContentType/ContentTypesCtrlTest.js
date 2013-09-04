'use strict';

describe('ContentTypesCtrl: ', function () {

  var scope;
  var controller;

  //init app
  beforeEach(module('shiftContentApp'));

  //init controller
  beforeEach(inject(function($rootScope, $controller){
    scope = $rootScope.$new();
    controller = $controller;
  }));


  //-------------------------------------------------------------------------


  //sort types
  it('sorts types on init', function(){

    var types = [
      {name: 'Z goes last'},
      {name: 'Me is in the middle'},
      {name: 'A goes first'}
    ];

    controller('ContentTypesCtrl', {
      $scope: scope,
      types: types
    });

    expect(scope.types[0]).toEqual(types[2]);
    expect(scope.types[1]).toEqual(types[1]);
    expect(scope.types[2]).toEqual(types[0]);
  });

  //can go to type editor screen
  it('can go to type editor screen', function(){
    controller('ContentTypesCtrl', {$scope: scope, types: []});

    var location;
    inject(function($location){
      location = $location;
    });
    expect(location.path()).toBe('/');

    var id = '123';
    scope.editType(id);
    expect(location.path()).toBe('/' + id + '/');
  });







});
