"use strict";angular.module("shiftContentApp",[]).config(["$routeProvider",function(a){a.when("/",{templateUrl:"/modules/shift-content-new/views/main.html",controller:"MainCtrl"}).otherwise({redirectTo:"/"})}]);var app=angular.module("shiftContentApp");app.controller("MainCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]);var app=angular.module("shiftContentApp");app.directive("shiftNavigation",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/navigation.html"}});var app=angular.module("shiftContentApp");app.directive("shiftBreadcrumbs",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/breadcrumbs.html"}});