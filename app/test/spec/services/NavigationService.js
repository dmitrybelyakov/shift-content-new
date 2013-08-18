'use strict';

describe('Service: NavigationService', function () {

  // load the service's module
  beforeEach(module('ShiftContentApp'));

  // instantiate service
  var NavigationService;
  beforeEach(inject(function (_NavigationService_) {
    NavigationService = _NavigationService_;
  }));

  it('should do something', function () {
    expect(!!NavigationService).toBe(true);
  });

});
