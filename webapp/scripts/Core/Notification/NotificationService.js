'use strict';
var app = angular.module('shiftContentApp');

/**
 * Notification service
 * Maintains multiple queues of notification messages each of which may
 * have a message test, type and a timeout in seconds an infinite.
 * You can display those queued messages via notifications directives in
 * your templates.
 */
app.service('NotificationService', function NotificationService($timeout) {

  //init service
  var service = {};


  service.queues = [];
  service.growls = [];
  var growlTimeout = 2000;

  /*
   * Notifications
   */

  //prepare queue name
  service.queueName = function(name){
    return name.toString().replace(/[^A-Z0-9]/ig, '');
  };

  //parses timeout to return milliseconds
  service.parseTimeout = function(t)
  {
    if(!t) {
      return 0;
    }
    if(typeof t.indexOf === 'function') {
      if(t.indexOf('s') !== -1 && t.indexOf('ms') == -1){
        t = parseFloat(t) * 1000;
      } else{
        t = parseFloat(t);
      }
    }

    return t;
  }

  //get queue by name
  service.getQueue = function(queue){
    if(!queue) {
      return;
    }

    queue = service.queueName(queue);
    if(!service.queues[queue]) {
      service.queues[queue] = []; //create empty to bind
    }

    return service.queues[queue];
  };

  //get all queues
  service.getAllQueues = function(){
    return service.queues;
  };


  //send notification to specified queue
  service.send = function(queue, type, message, timeout) {

    //convert to proper name
    queue = service.queueName(queue);

    //create if required
    if(!service.queues[queue]) {
      service.queues[queue] = [];
    }

    //prepare timeout
    if(!timeout) {
      timeout = 5000; //default timeout
    } else {
      timeout = service.parseTimeout(timeout);
    }

    //push message to queue
    var id = Math.random().toString(36).slice(2);
    service.queues[queue].push({
      id: id,
      queue: queue,
      type: type,
      message: message,
      timeout: timeout
    });

    //and remove after timeout
    if(timeout !== 'infinite') {
      $timeout(function(){
        service.removeNotification(id);
      }, timeout);
    }
  };

  //remove message from queue (close)
  service.removeNotification = function(id) {
    for(var queue in service.queues) {
      if(service.queues.hasOwnProperty(queue)) {
        for(var index in service.queues[queue]) {
          if(service.queues[queue].hasOwnProperty(index)) {
            if(service.queues[queue][index].id === id) {
              service.queues[queue].splice(index, 1);
            }
          }
        }
      }
    }
  };



  /*
   * Growls
   */

  //send out growl notification
  service.growl = function(message, timeout){
    var id = Math.random().toString(36).slice(2);


    //add growl
    service.growls.push({
      id: id,
      message: message
    });

    //prepare timeout
    if(!timeout) {
      timeout = growlTimeout;
    } else {
      timeout = service.parseTimeout(timeout);
    }

    //remove after timeout
    $timeout(function(){
      service.removeGrowl(id);
    }, timeout);
  };

  //remove growl from queue
  service.removeGrowl = function(id){
    for(var index in service.growls) {
      if(service.growls.hasOwnProperty(index) &&
        service.growls[index].id === id) {
        service.growls.splice(index, 1);
      }
    }
  };

  //get growls
  service.getGrowls = function(){
    return service.growls;
  };


  //return service
  return service;

});
