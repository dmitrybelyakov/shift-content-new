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
    cache.remove = jasmine.createSpy('remove from cache');
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

  /*
   * Repo cache
   */
  describe('Repository cache', function(){

    it('clears cache on init', function(){
      provideCache();
      inject(function(TypeRepository){
        var repo = TypeRepository;
        expect(cache.removeAll).toHaveBeenCalled();
      });
    });

    it('can cache single type and update cached list', function () {

      //we'll cache that
      var type = {
        id: 123,
        name: 'Updated'
      };

      //mock cache
      cache.get = function(cache){
        if(cache === 'all') {
          return [
            {id: 123, name: 'previously cached'},
            {id: 456, name: 'something else'}
          ];
        }
      };
      provideCache();

      var injector = getInjector();
      var repo = injector.get('TypeRepository');
      repo.cache.type(type);

      //assert item cached
      expect(cache.put).toHaveBeenCalledWith(type.id.toString(), type);

      //assert old item removed from list
      expect(cache.put).toHaveBeenCalledWith('all', [
        {id: 456, name: 'something else'}, type
      ]);
    });

    it('can cache list and each one item', function () {

      //we'll cache the list
      var type1 = {id: 123, name: 'an item'};
      var type2 = {id: 456, name: 'something else'};
      var types = [type1, type2];

      provideCache();
      var injector = getInjector();
      var repo = injector.get('TypeRepository');
      repo.cache.list(types);

      //expect list cached
      expect(cache.put).toHaveBeenCalledWith('all', types);

      //expect items cached individually
      expect(cache.put).toHaveBeenCalledWith(type1.id.toString(), type1);
      expect(cache.put).toHaveBeenCalledWith(type2.id.toString(), type2);
    });


    it('can remove item cache and remove it from list cache', function () {

      //we'll use the list
      var type1 = {id: 123, name: 'an item'};
      var type2 = {id: 456, name: 'something else'};
      var types = [type1, type2];

      cache.get  = function(){return types};

      provideCache();
      var injector = getInjector();
      var repo = injector.get('TypeRepository');
      repo.cache.remove(type1);

      //expect item cache removed
      expect(cache.remove).toHaveBeenCalledWith(type1.id.toString());

      //expect item cache removed from list
      expect(cache.put).toHaveBeenCalledWith('all', [type2]); //no type1
    });


  }); //repo cache



  /*
   * Repo functions
   */
  describe('Repository API', function(){

    it('returns type from cache if found', function () {
      var item = {id: 123};
      cache.get = function(){return item};
      provideCache();
      var injector = getInjector();
      var repo = injector.get('TypeRepository');
      expect(repo.get(123)).toEqual(item);
    });

    it('can fetch type from backend and cache', function () {

      var item = {id: 123};

      provideCache();
      var injector = getInjector();
      var http = injector.get('$httpBackend');
      var repo = injector.get('TypeRepository');

      //mock cacher
      repo.cache.type = jasmine.createSpy('cache type');

      //expect proper backend call
      http.expectGET(baseUrl + '1/').respond(200, item);
      var promise = repo.get(1);

      //expect proper data
      promise.then(function(data){
        expect(data).toEqual(item);
      });

      //go!
      http.flush();

      //assert type cached
      expect(repo.cache.type).toHaveBeenCalledWith(item);
    });

    it('can return list from cache', function () {
      var list = ['we', 'are', 'cached'];
      cache.get = function(){return list};
      provideCache();
      var injector = getInjector();
      var repo = injector.get('TypeRepository');
      expect(repo.query()).toEqual(list);
    });

    it('can fetch list from backend and cache', function () {
      var list = ['we', 'are', 'cached'];

      provideCache();
      var injector = getInjector();
      var http = injector.get('$httpBackend');
      var repo = injector.get('TypeRepository');

      //mock cacher
      repo.cache.list = jasmine.createSpy('cache list');

      //expect proper backend call
      http.expectGET(baseUrl).respond(200, list);
      var promise = repo.query();

      //expect proper data
      promise.then(function(data){
        expect(data).toEqual(list);
      });

      //go!
      http.flush();

      //assert list cached
      expect(repo.cache.list).toHaveBeenCalledWith(list);
    });

    it('can create type and put to cache', function () {
      var type = {id: 123};

      provideCache();
      var injector = getInjector();
      var http = injector.get('$httpBackend');
      var repo = injector.get('TypeRepository');

      //mock cacher
      repo.cache.type = jasmine.createSpy('cache type');

      //expect proper backend call
      http.expectPOST(baseUrl).respond(200, type);
      var promise = repo.create(type);

      //expect proper data
      promise.then(function(response){
        expect(response.data).toEqual(type);
      });

      //go!
      http.flush();

      //assert type cached
      expect(repo.cache.type).toHaveBeenCalledWith(type);
    });

    it('can update type and put to cache', function () {
      var type = {id: 123};

      provideCache();
      var injector = getInjector();
      var http = injector.get('$httpBackend');
      var repo = injector.get('TypeRepository');

      //mock cacher
      repo.cache.type = jasmine.createSpy('cache type');

      //expect proper backend call
      http.expectPOST(baseUrl + type.id + '/').respond(200, type);
      var promise = repo.update(type);

      //expect proper data
      promise.then(function(response){
        expect(response.data).toEqual(type);
      });

      //go!
      http.flush();

      //assert type cached
      expect(repo.cache.type).toHaveBeenCalledWith(type);
    });

    it('can delete type and remove from cache', function () {
      var type = {id: 123};

      provideCache();
      var injector = getInjector();
      var http = injector.get('$httpBackend');
      var repo = injector.get('TypeRepository');

      //mock cacher
      repo.cache.remove = jasmine.createSpy('cache type');

      //expect proper backend call
      http.expectDELETE(baseUrl + type.id + '/').respond(204);
      var promise = repo.delete(type);

      //expect proper data
      promise.then(function(response){
        expect(response.data).toBeUndefined();
      });

      //go!
      http.flush();

      //assert type cache removed
      expect(repo.cache.remove).toHaveBeenCalledWith(type);
    });


  });



}); //tests end here
