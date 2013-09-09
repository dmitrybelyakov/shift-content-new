'use strict';
describe('List: ', function() {
  var p, ptor;

  beforeEach(function() {
    ptor = protractor;
    p = ptor.getInstance();
    p.get('types/');
  });

  it('should show types list screen', function() {
    var t = p.findElement(ptor.By.css('td.title'));
    expect(t.getText()).toContain('Content types');
  });


  /*
   * New type form
   */
  describe('New type form: ', function(){

    var form;


    beforeEach(function(){
      var map = {
        container: {id: 'newTypeForm'},
        open: {partialLinkText: 'Add'},
        close: {partialLinkText: 'Cancel'},
        close2: {partialLinkText: 'Discard'},
        submit: {css: '#newTypeForm input[type="submit"]'},
        name: {
          input: {name: 'name'},
          errors: {id: 'nameErrors'}
        },
        description: {
          input: {name: 'description'},
          errors: {id: 'descriptionErrors'}
        }
      };

      form = ptor.po(map);
    });



    it('can show and hide form', function () {
      expect(form.container().isDisplayed()).toBe(false);

      form.open().click();
      expect(form.container().isDisplayed()).toBe(true);

      form.close().click();
      expect(form.container().isDisplayed()).toBe(false);

      form.open().click();
      form.close2().click();
      expect(form.container().isDisplayed()).toBe(false);
    });


    it('can catch frontend validation errors', function () {

      form.open().click();
      form.submit().click();

      expect(form.name.errors().isDisplayed()).toBe(true);
      var required = ptor.By.css('li:first-child');
      required = form.name.errors().findElement(required);
      expect(required.isDisplayed()).toBe(true);
      expect(required.getText()).toContain('required');

    });

  }); //new type from











});
