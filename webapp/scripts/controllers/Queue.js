'use strict';
var app = angular.module('shiftContentApp');

app.controller('Queue', function ($scope, NotificationService) {

  var service = NotificationService;


  $scope.addMessage = function(){
    service.notification(123, 'message', 'Example message...', 1);
    console.info(service.getAllQueues());
  };

  $scope.addMessage2 = function(){
    service.notification('some name', 'message', 'Example message...', 1);
  };



});
