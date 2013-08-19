"use strict";angular.module("shiftContentApp",["ngRoute","ngAnimate"]).config(["$provide","$routeProvider","$locationProvider",function(a,b,c){var d="/";b.when("/",{templateUrl:"/modules/shift-content-new/views/main.html",controller:"MainCtrl"}).when("/index/",{redirectTo:d}).when("/another/",{templateUrl:"/modules/shift-content-new/views/another.html",controller:"MainCtrl"}).when("/manage/:contentId/",{templateUrl:"/modules/shift-content-new/views/manage-content.html",controller:"MainCtrl"}).when("/feeds/",{templateUrl:"/modules/shift-content-new/views/manage-feeds.html",controller:"MainCtrl"}).when("/types/",{templateUrl:"/modules/shift-content-new/views/content-types.html",controller:"MainCtrl"}).when("/field-types/",{templateUrl:"/modules/shift-content-new/views/field-types.html",controller:"MainCtrl"}).when("/filter-types/",{templateUrl:"/modules/shift-content-new/views/filter-types.html",controller:"MainCtrl"}).when("/validator-types/",{templateUrl:"/modules/shift-content-new/views/validator-types.html",controller:"MainCtrl"}).otherwise({redirectTo:"/"}),c.html5Mode(!0).hashPrefix("!")}]);var app=angular.module("shiftContentApp");app.controller("MainCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]);var app=angular.module("shiftContentApp");app.factory("NavigationService",function(){var a=[{title:"Manage content",items:[{label:"Static page",href:"manage/11/"},{label:"Attachment",href:"manage/12/"},{label:"Shop Item",href:"manage/13/"}]},{title:"Manage content",items:[{label:"Feed collections",href:"feeds/"}]},{title:"Content settings",items:[{label:"Content types",href:"types/"},{label:"Field types",href:"field-types/"},{label:"Filter types",href:"filter-types/"},{label:"Validator types",href:"validator-types/"}]}];return a});var app=angular.module("shiftContentApp");app.directive("shiftNavigation",["NavigationService",function(a){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/parts/navigation.html",controller:["$scope","$location",function(b,c){var d=a,e=function(){var a=c.path().substring(1);for(var b in d)for(var e in d[b].items)d[b].items[e].active=a===d[b].items[e].href?!0:!1};e(),b.$on("$routeChangeStart",function(){e()}),b.navigation=d}]}}]);var app=angular.module("shiftContentApp");app.directive("shiftBreadcrumbs",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/parts/breadcrumbs.html"}});