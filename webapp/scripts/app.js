'use strict';

//set up app
var app = angular.module('shiftContentApp', [
  'ngRoute',
  'ngAnimate'
]);

//configure
app.config(function ($locationProvider, $routeProvider) {

    //set to html5 mode
    $locationProvider.html5Mode(true).hashPrefix('!');

    //configure routes
    var router = $routeProvider;

    //views base path
    var views = '/modules/shift-content-new/views';

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

    //test
    router.when('/another/', {
      controller: 'MainCtrl',
      templateUrl: views + '/another.html'
    });

    //manage content items
    router.when('/manage/:contentId/', {
      controller: 'MainCtrl',
      templateUrl: views + '/another.html'
    });

    //manage feed collections
    router.when('/feeds/', {
      controller: 'Queue',
      templateUrl: views + '/manage-feeds.html'
    });

    //manage content types
    router.when('/types/', {
      controller: 'ContentTypes',
      templateUrl: views + '/content-types/list.html',
      resolve: {
        types: ['ContentTypes', function(ContentTypes){
          return ContentTypes.query();
        }]
      }
    });
    router.when('/types/:id/', {
      controller: 'ContentType',
      templateUrl: views + '/content-types/type.html',
      resolve: {
        type: ['ContentTypes', '$route', function(ContentTypes, $route){
          return ContentTypes.get($route.current.params.id);
        }]
      }
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

