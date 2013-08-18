'use strict';

angular.module('shiftContentApp', ['ngRoute', 'ngAnimate'])
  .config(function ($provide, $routeProvider, $locationProvider) {

    var base = '/backend/modules/content-new/';

    $routeProvider
      .when(base, {
        templateUrl: '/modules/shift-content-new/views/main.html',
        controller: 'MainCtrl'
      })
      .when(base + 'another/', {
        templateUrl: '/modules/shift-content-new/views/another.html',
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: base
      });

    $locationProvider
      .html5Mode(true)
      .hashPrefix('!');

  });
