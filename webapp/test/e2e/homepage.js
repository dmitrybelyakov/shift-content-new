describe('Module homepage', function() {
  var p, prot;

  beforeEach(function() {
    prot = protractor;
    p = prot.getInstance();
  });

  it('should open module home and load index', function() {
    p.get('');
    var title = p.findElement(prot.By.css('table.pageHeader td.title'));
    expect(title.getText()).toContain('List view');
  });

  it('should show feeds home screen', function() {
    p.get('feeds/');
    var title = p.findElement(prot.By.css('table.pageHeader td.title'));
    expect(title.getText()).toContain('Manage feeds');
  });

});
