//
// test/e2e/controllers/controllersSpec.js
//
describe("E2E: Testing Controllers", function() {

  beforeEach(function() {
    browser().navigateTo('/backend/modules/content-new/');
  });

  it('Should open home and display index view', function() {
    browser().navigateTo('/backend/modules/content-new/');
    expect(element('table.pageHeader td.title').html()).toContain('List view')
  });


});
