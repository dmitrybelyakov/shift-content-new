'use strict';
var app = angular.module('shiftContentApp');

app.controller('Queue', function ($scope, NotificationService) {

  var service = NotificationService;


  $scope.addMessage = function(){
    service.notify(123, 'message', 'Example message...');
//    console.info(service.getAllQueues());
  };

  $scope.addMessage2 = function(){
    service.notify('somename', 'error', 'Example message...', 'infinite');
  };



});
