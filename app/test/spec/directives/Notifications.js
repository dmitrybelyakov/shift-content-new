'use strict';

describe('Directive: Notifications', function () {

  // load the directive's module
  beforeEach(module('shiftContentApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<-notifications></-notifications>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the Notifications directive');
  }));
});
