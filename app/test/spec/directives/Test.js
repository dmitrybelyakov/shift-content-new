'use strict';

describe('Directive: Test', function () {
  beforeEach(module('testApp'));

  var element;

  it('should make hidden element visible', inject(function ($rootScope, $compile) {
    element = angular.element('<-test></-test>');
    element = $compile(element)($rootScope);
    expect(element.text()).toBe('this is the Test directive');
  }));
});
