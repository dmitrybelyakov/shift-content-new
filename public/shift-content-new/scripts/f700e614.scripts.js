"use strict";angular.module("shiftContentApp",["ngRoute","ngAnimate"]).config(["$provide","$routeProvider","$locationProvider",function(a,b,c){var d="/";b.when(d,{templateUrl:"/modules/shift-content-new/views/main.html",controller:"MainCtrl"}).when(d+"index/",{redirectTo:d}).when(d+"another/",{templateUrl:"/modules/shift-content-new/views/another.html",controller:"MainCtrl"}).otherwise({redirectTo:d}),c.html5Mode(!0).hashPrefix("!")}]);var app=angular.module("shiftContentApp");app.controller("MainCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]);var app=angular.module("shiftContentApp");app.directive("shiftNavigation",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/navigation.html"}});var app=angular.module("shiftContentApp");app.directive("shiftBreadcrumbs",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/breadcrumbs.html"}});