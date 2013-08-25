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
  service.notify = function(queue, type, message, timeout) {

    //convert to proper name
    queue = service.queName(queue);

    //no queue?
    if(!service.queues[queue]) {
      service.queues[queue] = [];
    }

    //push message to queue
    service.queues[queue].push({
      id: Math.random().toString(36).slice(2),
      queue: queue,
      type: type,
      message: message,
      timeout: timeout
    });
  };

    //get queue by name
    service.getQueue = function(queue){
      queue = service.queName(queue);
      if(!service.queues[queue]) {
        service.queues[queue] = []; //create empty to bind
      }

      return service.queues[queue];
    };

    //get all queues
    service.getAllQueues = function(){
      return service.queues;
    };

    //remove message from queue (close)
    service.removeNotification = function(id) {
        console.info('NOW REMOVE MESSAGE WITH ID ' + id);

        for(var queue in service.queues) {
          if(service.queues.hasOwnProperty(queue)) {
            for(var index in service.queues[queue]) {
              if(service.queues[queue].hasOwnProperty(index)) {
                if(service.queues[queue][index].id === id) {
                  console.info('Found message in queue ' + queue);
                  console.info(service.queues[queue][index]);
                  service.queues[queue].splice(index, 1);
                  console.info(service.queues[queue]);
                }
              }
            }
          }
        }

    };

  //return service
  return service;

});
