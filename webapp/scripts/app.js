'use strict';

//set up app
var app = angular.module('shiftContentApp', [
  'ngRoute',
  'ngAnimate'
]);

//base path to views
app.constant('viewsBase', '/modules/shift-content-new/views');

//configure
app.config(function ($locationProvider, $routeProvider, viewsBase) {

    //set to html5 mode
    $locationProvider.html5Mode(true).hashPrefix('!');

    var router = $routeProvider;
    var views = viewsBase;

    //index
    router.otherwise({
      redirectTo: '/'
    });
    router.when('/', {
      controller: 'MainCtrl',
      templateUrl: views + '/main.html'
    });
    router.when('/index/', {
      redirectTo: '/'
    });

    //manage content items
    router.when('/manage/:contentId/', {
      controller: 'MainCtrl',
      templateUrl: views + '/another.html'
    });

    //manage feed collections
    router.when('/feeds/', {
      controller: 'MainCtrl',
      templateUrl: views + '/manage-feeds.html'
    });

    //manage field types
    router.when('/field-types/', {
      controller: 'MainCtrl',
      templateUrl: views + '/field-types.html'
    });

    //manage filter types
    router.when('/filter-types/', {
      controller: 'MainCtrl',
      templateUrl: views + '/filter-types.html'
    });

    //manage validator types
    router.when('/validator-types/', {
      controller: 'MainCtrl',
      templateUrl: views + '/validator-types.html'
    });


  }
); //config ends here

