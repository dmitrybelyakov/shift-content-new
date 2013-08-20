'use strict';

describe('Directive: ViewResolver', function () {
  beforeEach(module('shiftContentApp'));

  var element;

  it('should make hidden element visible', inject(function ($rootScope, $compile) {
    element = angular.element('<-view-resolver></-view-resolver>');
    element = $compile(element)($rootScope);
    expect(element.text()).toBe('this is the ViewResolver directive');
  }));
});
