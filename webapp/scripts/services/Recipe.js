'use strict';
var app = angular.module('shiftContentApp');


/**
 * Recipe resource
 * Returns resource preconfigured to api endpoint.
 */
app.factory('Recipe', function ($resource) {

  var url =  '/api/content/:id/';
  var defaults = {
    id: '@id'
  };

  return $resource(url, defaults);
});

/**
 * Multiloader
 */
app.factory('MultiRecipeLoader', function(Recipe, $q){
  return function(){

    var delay = $q.defer();
    Recipe.query(
      //success
      function(recipes){ delay.resolve(recipes);},

      //reject
      function(){delay.reject('Unable to load from backend');}
    );

    return delay.promise();
  };
});


/**
 * Regular loader
 */
app.factory('RecipeLoader', function(Recipe, $route, $q){
  return function(){

    var delay = $q.defer();
    var itemId = $route.current.params.id;

    Recipe.get(
      {id: itemId},
      function(recipe){delay.resolve(recipe);},
      function(){delay.reject('Unable to fetch recipe: ' + itemId)}
    );

    return delay.promise;
  };

});
