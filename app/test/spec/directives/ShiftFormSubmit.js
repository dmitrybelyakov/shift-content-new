'use strict';

describe('Directive: ShiftFormSubmit', function () {

  // load the directive's module
  beforeEach(module('shiftContentApp'));

  var element,
    scope;

  beforeEach(inject(function ($rootScope) {
    scope = $rootScope.$new();
  }));

  it('should make hidden element visible', inject(function ($compile) {
    element = angular.element('<-shift-form-submit></-shift-form-submit>');
    element = $compile(element)(scope);
    expect(element.text()).toBe('this is the ShiftFormSubmit directive');
  }));
});
