'use strict';

//set up app
var app = angular.module('shiftContentApp', [
  'ngRoute',
  'ngAnimate',
  'ngResource'
]);

//configure
app.config(function ($locationProvider, $routeProvider) {

    //set to html5 mode
    $locationProvider.html5Mode(true).hashPrefix('!');

    //configure routes
    var router = $routeProvider;

    //index
    router.otherwise({
      redirectTo: '/'
    });
    router.when('/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/main.html'
    });
    router.when('/index/', {
      redirectTo: '/'
    });

    //test
    router.when('/another/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/another.html'
    });

    //manage content items
    router.when('/manage/:contentId/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/another.html'
    });

    //manage feed collections
    router.when('/feeds/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/manage-feeds.html'
    });

    //manage content types
    router.when('/types/', {
      controller: 'ContentTypes',
      templateUrl: '/modules/shift-content-new/views/content-types.html',
      resolve: {
        types: ['MultiTypeLoader', function(MultiTypeLoader){
          return new MultiTypeLoader();
        }]
      }
    });

    //manage field types
    router.when('/field-types/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/field-types.html'
    });

    //manage filter types
    router.when('/filter-types/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/filter-types.html'
    });

    //manage validator types
    router.when('/validator-types/', {
      controller: 'MainCtrl',
      templateUrl: '/modules/shift-content-new/views/validator-types.html'
    });


  }
); //config ends here

