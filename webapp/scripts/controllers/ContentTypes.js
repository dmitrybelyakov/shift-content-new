'use strict';
var app = angular.module('shiftContentApp');
app.controller('ContentTypes', function ($scope, ContentTypesService) {

  //get service
  var service = ContentTypesService;

  console.info(service.getTypes());



});
