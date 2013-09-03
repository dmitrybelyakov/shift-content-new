'use strict';
var app = angular.module('shiftContentApp');


/**
 * Content types repository
 * Used to perform basic persistence operations by communicating with backend.
 */
app.factory('TypeRepository', function ($http) {

  //base endpoint
  var baseUrl = '/api/content/types/';

  //resource-like class to communicate with backend
  var Repository = {};

  //get by id
  Repository.get = function(id){
    return $http.get(baseUrl + id + '/').then(function(response){
      return response.data;
    });
  };

  //query to get all
  Repository.query = function(){
    return $http.get(baseUrl).then(function(response){
      return response.data;
    });
  };

  //create type
  Repository.create = function(data) {
    return $http.post(baseUrl, data);
  };

  //delete type
  Repository.delete = function(type) {
    return $http.delete(baseUrl + type.id +'/');
  };

  return Repository;
});

