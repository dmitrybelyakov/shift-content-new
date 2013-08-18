'use strict';

angular.module('shiftContentApp', ['ngRoute', 'ngAnimate'])
  .config(function ($provide, $routeProvider, $locationProvider) {

    var base = '/';


    $routeProvider
      .when('/', {
        templateUrl: '/modules/shift-content-new/views/main.html',
        controller: 'MainCtrl'
      })
      .when('/index/', {
        redirectTo: base
      })
      .when('/another/', {
        templateUrl: '/modules/shift-content-new/views/another.html',
        controller: 'MainCtrl'
      })
      .when('/manage/:contentId/', {
        templateUrl: '/modules/shift-content-new/views/manage-content.html',
        controller: 'MainCtrl'
      })
      .when('/feeds/', {
        templateUrl: '/modules/shift-content-new/views/manage-feeds.html',
        controller: 'MainCtrl'
      })
      .when('/types/', {
        templateUrl: '/modules/shift-content-new/views/content-types.html',
        controller: 'MainCtrl'
      })
      .when('/field-types/', {
        templateUrl: '/modules/shift-content-new/views/field-types.html',
        controller: 'MainCtrl'
      })
      .when('/filter-types/', {
        templateUrl: '/modules/shift-content-new/views/filter-types.html',
        controller: 'MainCtrl'
      })
      .when('/validator-types/', {
        templateUrl: '/modules/shift-content-new/views/validator-types.html',
        controller: 'MainCtrl'
      })
      .otherwise({
        redirectTo: '/'
      });

    $locationProvider
      .html5Mode(true)
      .hashPrefix('!');

  });

