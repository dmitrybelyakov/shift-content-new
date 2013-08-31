'use strict';
var app = angular.module('shiftContentApp');


/**
 * Content repository
 * Alternative implementation of content repository built with $http service
 * instead of resource.
 */
app.factory('ContentTypes', function ($http) {

  //base endpoint
  var baseUrl = '/api/content/types/';

  //resource-like class to communicate with backend
  var Repository = {};

  //get by id
  Repository.get = function(id){
    return $http.get(baseUrl + id + '').then(function(response){
      return new ContentType(response.data);
    });
  };

  //query to get all
  Repository.query = function(){
    return $http.get(baseUrl).then(function(response){
      return response.data;
    });
  };

  Repository.create = function(data) {
    return $http.post(baseUrl, data);
  };

  Repository.delete = function(type) {
    return $http.delete(baseUrl + type.id +'/');
  };


  return Repository;
});

