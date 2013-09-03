'use strict';
var app = angular.module('shiftContentApp');


/**
 * Content types repository
 * Used to perform basic persistence operations by communicating with backend.
 */
app.factory('TypeRepository', function ($http, $angularCacheFactory) {

  //configure cache
  $angularCacheFactory('contentTypes', {
    maxAge: 90000,
    cacheFlushInterval: 600000,
    aggressiveDelete: true,
    storageMode: 'localStorage'
  });

  var cache = $angularCacheFactory.get('contentTypes');
  cache.removeAll();

  var _ = window._;
  var baseUrl = '/api/content/types/';
  var Repository = {};


  //get by id
  Repository.get = function(id){
    id = id.toString();
    if(cache.get(id)) {
      return cache.get(id);
    } else {
      return $http.get(baseUrl + id + '/')
        .success(function(data){
          cache.put(id, data);
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
      return $http.get(baseUrl).then(function(response){
        cache.put('all', response.data);
        _.each(response.data, function(el){
          cache.put(el.id.toString(), el);
        });
        return response.data;
      });
    }
  };

  //create type
  Repository.create = function(data) {
    return $http.post(baseUrl, data)
      .success(function(response){
        cache.put(response.id.toString(), response);
        cache.get('all').push(response);
      });
  };

  //delete type
  Repository.delete = function(type) {
    return $http.delete(baseUrl + type.id +'/')
      .success(function(){
        cache.remove(type.id);
        cache.put('all', _.reject(cache.get('all'), function(e){
          return e.id === type.id;
        }));
      });


  };

  return Repository;
});

