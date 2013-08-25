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
  service.queName = function(name){
    return name.toString().replace(/[^A-Z0-9]/ig, '');
  };

  //send notification to specified queue
  service.notify = function(queue, type, message, timeout) {

    //convert to proper name
    queue = service.queName(queue);

    //create if required
    if(!service.queues[queue]) {
      service.queues[queue] = [];
    }

    //prepare timeout
    if(!timeout) {
      timeout = 5000; //default timeout
    } else if(timeout.indexOf('ms') !== -1){
      timeout = parseFloat(timeout);
    } else if (timeout.indexOf('s') !== -1) {
      timeout = parseFloat(timeout);
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
  service.growl = function(message){
    var id = Math.random().toString(36).slice(2);

    //add growl
    service.growls.push({
      id: id,
      message: message
    });

    //remove after timeout
    $timeout(function(){
      service.removeGrowl(id);
    }, growlTimeout);
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
