'use strict';

describe('Service: ContentTypesService', function () {

  // load the service's module
  beforeEach(module('ShiftContentApp'));

  // instantiate service
  var ContentTypesService;
  beforeEach(inject(function (_ContentTypesService_) {
    ContentTypesService = _ContentTypesService_;
  }));

  it('should do something', function () {
    expect(!!ContentTypesService).toBe(true);
  });

});
