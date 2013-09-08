'use strict';
var app = angular.module('shiftContentApp');

/**
 * Content type routes
 * Defines routing under content type editor namespace that includes managing
 * types, editing types, managing fields and their attributes.
 */
app.config(function ($routeProvider, viewsBase) {

  var router = $routeProvider;
  var views = viewsBase;

  router.when('/types/', {
    controller: 'ContentTypesCtrl',
    templateUrl: views + '/content-types/list.html',
    resolve: {
      types: ['TypeRepository', '$timeout', function(TypeRepository, $timeout){
        return TypeRepository.query();
      }]
    }
  });

  router.when('/types/:id/', {
    controller: 'ContentTypeCtrl',
    templateUrl: views + '/content-types/type.html',
    resolve: {
      type: ['TypeRepository', '$route', function(TypeRepository, $route){
        return TypeRepository.get($route.current.params.id);
      }],
      fieldTypes: ['FieldTypeRepository', function(FieldTypeRepository){
        return FieldTypeRepository.query();
      }]
    }
  });

});