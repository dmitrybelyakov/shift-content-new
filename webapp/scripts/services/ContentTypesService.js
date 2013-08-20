'use strict';

angular.module('shiftContentApp')
  .service('ContentTypesService', function ContentTypesService() {

    //init service
    var service = {};

    /**
     * Get types
     * Returns an array of content types.
     */
    service.getTypes = function(){

      var types = [
        {id: 123, title: 'me is type one'},
        {id: 456, title: 'me is type two'},
        {id: 789, title: 'me is type three'}
      ];

      return types;
    };

    return service;

  });
