'use strict';
describe('Module homepage', function() {
  var p, ptor;

  beforeEach(function() {
    ptor = protractor;
    p = ptor.getInstance();
  });

  it('should open module home and load index', function() {
    p.get('');
    var title = p.findElement(ptor.By.css('table.pageHeader td.title'));
    expect(title.getText()).toContain('List view');
  });

  it('should show feeds home screen', function() {
    p.get('feeds/');
    var title = p.findElement(ptor.By.css('table.pageHeader td.title'));
    expect(title.getText()).toContain('Manage feeds');
  });

});
