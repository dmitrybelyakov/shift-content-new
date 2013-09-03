'use strict';
var app = angular.module('shiftContentApp');


/**
 * Field type repository
 * This is a read-only api to fetch possible content fields from the server.
 */
app.factory('FieldTypeRepository', function ($http, $angularCacheFactory) {

  //configure cache
  $angularCacheFactory('contentFields', {
    maxAge: 90000,
    cacheFlushInterval: 600000,
    aggressiveDelete: true,
    storageMode: 'localStorage'
  });

  var cache = $angularCacheFactory.get('contentFields');

  var baseUrl = '/api/content/field-types/';
  var Repository = {};

  //query to get all
  Repository.query = function(){
    if(cache.get('all')) {
      return cache.get('all');
    } else {
      return $http.get(baseUrl).then(function(response){
        cache.put('all', response.data);
        return response.data;
      });
    }
  };

  return Repository;
});

