'use strict';

describe('Directive: InjectHeight', function () {
  beforeEach(module('shiftContentApp'));

  var element;

  it('should make hidden element visible', inject(function ($rootScope, $compile) {
    element = angular.element('<-inject-height></-inject-height>');
    element = $compile(element)($rootScope);
    expect(element.text()).toBe('this is the InjectHeight directive');
  }));
});
