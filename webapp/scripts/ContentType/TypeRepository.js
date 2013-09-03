'use strict';
var app = angular.module('shiftContentApp');


/**
 * Content types repository
 * Used to perform basic persistence operations by communicating with backend.
 */
app.factory('TypeRepository', function ($http, $cacheFactory) {


  var baseUrl = '/api/content/types/';
  var cache = $cacheFactory.get('$http');
  var Repository = {};

  //get by id
  Repository.get = function(id){
    var url = baseUrl + id + '/';
    return $http.get(url, {cache: true}).then(function(response){
      return response.data;
    });
  };

  //query to get all
  Repository.query = function(){
    return $http.get(baseUrl,{cache: true}).then(function(response){
      return response.data;
    });
  };

  //create type
  Repository.create = function(data) {
    cache.remove(baseUrl);
    return $http.post(baseUrl, data);
  };

  //delete type
  Repository.delete = function(type) {
    cache.remove(baseUrl);
    cache.remove(baseUrl + type.id +'/');
    return $http.delete(baseUrl + type.id +'/');
  };

  return Repository;
});

