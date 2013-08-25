'use strict';
var app = angular.module('shiftContentApp');

app.service('NotificationService', function NotificationService() {

  //init service
  var service = {};

  //queues container
  service.queues = [];

  //prepare queue name
  service.queName = function(name){
    return name.toString().replace(/[^A-Z0-9]/ig, "")
  };

  //send notification to specified queue
  service.notification = function(queue, type, message, timeout) {

    //convert to proper name
    queue = service.queName(queue);

    //no queue?
    if(!service.queues[queue]) {
      service.queues[queue] = [];
    }

    //push message to queue
    service.queues[queue].push({
      type: type,
      message: message,
      timeout: timeout
    });
  };

    //get queue by name
    service.getQueue = function(queue){
      queue = service.queName(queue);
      if(service.queues[queue]) {
        service.queues[queue]
      } else {
        return [];
      }
    };

    service.getAllQueues = function(){
      return service.queues;
    };

  //return service
  return service;

});
