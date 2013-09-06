'use strict';
/**
 * TypeRepository unit tests.
 * This will test functionality of talking to backend API via $http service
 * and working with cache saved to WebStorage.
 *
 * A thing to notice is that our actual cache will change from test to test
 * to emulate use cases and since it created by $angularCacheFactory it
 * must be configured before each test differently. beforeEach won't help us
 * here that's why we created a helper method to configure cache factory inside
 * tests with whatever cache object you give it (default one will do fine).
 *
 * Additionally we need to properly inject the factory, that is why we use
 * inject() in each and every test.
 */
describe('TypeRepository: ', function () {

  var cache;
  var provideCache;
  var getInjector;
  var baseUrl = '/api/content/types/';

  //init module
  beforeEach(module('shiftContentApp'));


  //mock cache
  beforeEach(function(){
    cache = {};
    cache.removeAll = jasmine.createSpy('clear');
    cache.get = jasmine.createSpy('get cache');
    cache.put = jasmine.createSpy('put to cache');
  });

  //configure cache (call this before injections)
  beforeEach(function(){
    provideCache = function(){
      module(function($provide){
        $provide.factory('$angularCacheFactory', function(){
          var cacheFactory = function(){};
          cacheFactory.get = function(){ return cache;};
          return cacheFactory;
        });
      });
    };
  });

  //configure async injector
  beforeEach(function(){
    getInjector = function(){
      var injector;
      inject(function($injector){
        injector = $injector;
      });
      return injector;
    };
  });


  //-------------------------------------------------------------------------


  it('clears cache on init', function(){
    provideCache();
    inject(function(TypeRepository){
      var repo = TypeRepository;
      expect(cache.removeAll).toHaveBeenCalled();
    });
  });

  it('can fetch item by id from backend and cache', function(){

    provideCache();
    var injector = getInjector();
    var http = injector.get('$httpBackend');
    var repo = injector.get('TypeRepository');
    var item = {id: 123};

    http.expectGET(baseUrl + '1/').respond(200, item);
    var promise = repo.get(1);

    promise.then(function(data){
      expect(data).toEqual(item);
    });
    http.flush();

    expect(cache.put).toHaveBeenCalledWith(item.id.toString(), item);
  });


}); //tests end here
