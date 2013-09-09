'use strict';
var app = angular.module('shiftContentApp');


/**
 * Content types repository
 * Used to perform basic persistence operations by communicating with backend.
 */
app.factory('TypeRepository', function ($http, $angularCacheFactory, $window) {

  //configure cache
  $angularCacheFactory('contentTypes', {
    maxAge: 90000,
    cacheFlushInterval: 600000,
    aggressiveDelete: false,
    storageMode: 'localStorage'
  });

  var cache = $angularCacheFactory.get('contentTypes');
  cache.removeAll();

  var _ = $window._;
  var baseUrl = '/api/content/types/';
  var Repository = {};


  /*
   * Manage type cache
   */
  Repository.cache = {};

  //save item to cache and update within cached list
  Repository.cache.type = function(type) {
    var id = type.id.toString();
    cache.put(id, type);
    if(cache.get('all')) {
      var list = cache.get('all');
      list = _.reject(cache.get('all'), function(e){
        return e.id === type.id;
      });
      list.push(type);
      cache.put('all', list);
    }
  };

  //caches all types and each one item
  Repository.cache.list = function(types) {
    cache.put('all', types);
    _.each(types, function(el){
      cache.put(el.id.toString(), el);
    });
  };

  //removes type cache from repository
  Repository.cache.remove = function(type) {
    var id = type.id.toString();
    cache.remove(id);
    if(cache.get('all')) {
      var list = cache.get('all');
      list = _.reject(cache.get('all'), function(e){
        return e.id === type.id;
      });
      cache.put('all', list);
    }
  };

  /*
   * Types CRUD
   */

  Repository.test = function(){

    if(cache.get('all')) {
      return cache.get('all');
    } else {
      var promise = $http.get(baseUrl)
        .then(function(response){
          return response.data;
        });

      return promise;
    }
  };


  //get by id
  Repository.get = function(id){
    id = id.toString();
    if(cache.get(id)) {
      return cache.get(id);
    } else {
      return $http.get(baseUrl + id + '/')
        .success(function(data){
          Repository.cache.type(data);
        })
        .then(function(response){
          return response.data;
        });
    }
  };

  //query to get all
  Repository.query = function(){

    if(cache.get('all')) {
      return cache.get('all');
    } else {
      return $http.get(baseUrl)
        .success(function(data){
          Repository.cache.list(data);
        })
        .then(function(response){
          return response.data;
        });
    }
  };

  //create type
  Repository.create = function(data) {
    return $http.post(baseUrl, data)
      .success(function(response){
        Repository.cache.type(response);
      });
  };

  //update type
  Repository.update = function(data) {
    var id = data.id.toString();
    return $http.post(baseUrl + id + '/', data)
      .success(function(response){
        Repository.cache.type(response);
      });
  };

  //delete type
  Repository.delete = function(type) {
    return $http.delete(baseUrl + type.id +'/')
      .success(function(){
        Repository.cache.remove(type);
      });
  };

  return Repository;
});

