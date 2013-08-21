'use strict';
var app = angular.module('shiftContentApp');


/**
 * Recipe resource
 * Returns resource preconfigured to api endpoint.
 */
app.factory('ContentTypeRepository', function ($resource) {

//  window.alert('123');/

  var url =  '/api/content/:id/';
  var defaults = {
    id: '@id'
  };

  var methods = {
    get: {method: 'GET', isArray: false}
  };

  return $resource(url, defaults, methods);
});

