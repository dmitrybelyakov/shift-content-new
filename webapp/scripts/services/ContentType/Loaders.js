'use strict';
var app = angular.module('shiftContentApp');

/**
 * Multiloader
 */
app.factory('MultiTypeLoader', function(ContentTypeRepository, $q){
  return function(){

    var delay = $q.defer();
    ContentTypeRepository.query(
      function(recipes){ delay.resolve(recipes);},
      function(){delay.reject('Unable to load from backend');}
    );

    return delay.promise;
  };
});


/**
* Regular loader
*/
app.factory('TypeLoader', function(ContentTypeRepository, $route, $q){
  return function(){

    var delay = $q.defer();
    var itemId = $route.current.params.id;

    ContentTypeRepository.get(
      {id: itemId},
      function(recipe){delay.resolve(recipe);},
      function(){delay.reject('Unable to fetch recipe: ' + itemId);}
    );

    return delay.promise;
  };

});
