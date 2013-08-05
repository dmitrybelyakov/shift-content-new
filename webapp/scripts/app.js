'use strict';

angular.module('shiftContentApp', [])
  .config(function ($routeProvider) {
    $routeProvider
      .when('/', {
        templateUrl: '/modules/shift-content-new/views/main.html',
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });
  });