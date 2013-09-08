'use strict';
var po = function(protractor, map){

  //external?
  if(typeof map === 'string') {
    var path = require('path');
    map = require(path.resolve(__dirname, map));
  }

  var getter = function(by, what){
    return function(){
      return protractor.getInstance()
        .findElement(protractor.By[by](what));
    };
  };

  var traverse = function(map){
    var po = {};
    for(var property in map) {
      var by = Object.keys(map[property])[0];
      var what = map[property][by];
      if(typeof what === 'string') {
        po[property] = new getter(by, what);
      } else if(typeof what === 'object') {
        po[property] = traverse(map[property]);
      }
    }
    return po;
  };

  return traverse(map);
};

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

    //construct form object
    beforeEach(function(){
      form = new po(ptor, 'map.json');
//      var map = {
//        container: {id: 'newTypeForm'},
//        open: {partialLinkText: 'Add'},
//        close: {partialLinkText: 'Cancel'},
//        close2: {partialLinkText: 'Discard'},
//        submit: {css: '#newTypeForm input[type="submit"]'},
//        name: {
//          input: {name: 'name'},
//          errors: {id: 'nameErrors'}
//        },
//        description: {
//          input: {name: 'description'},
//          errors: {id: 'descriptionErrors'}
//        }
//      };
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

      var required = form.name.errors()
        .findElement(ptor.By.css('li:first-child'));
      expect(required.isDisplayed()).toBe(true);
      expect(required.getText()).toContain('required');

    });

  }); //new type from











});
