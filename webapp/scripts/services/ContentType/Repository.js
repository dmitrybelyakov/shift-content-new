'use strict';
var app = angular.module('shiftContentApp');


/**
 * Recipe resource
 * Returns resource preconfigured to api endpoint.
 */
app.factory('ContentTypeRepository', function ($resource) {

  var url =  '/api/content/types/:id/';
  var defaults = {
    id: '@id'
  };

  var methods = {
    get: {method: 'GET', isArray: false},
    save: {method: 'POST', isArray: true}
  };

  return $resource(url, defaults, methods);
});

