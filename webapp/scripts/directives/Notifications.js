'use strict';
var app = angular.module('shiftContentApp');

/**
 * Notifications directive
 * Use this in templates to output notifications sent to notification service.
 * Specify what message queue to listen to by giving your element and id
 * corresponding to queue name.
 */
app.directive('shiftNotifications', function (NotificationService) {
  return {
    restrict: 'A',
    scope: {},
    template:'<div class="notification {{notification.type}}" ' +
      'ng-repeat="notification in queue">' +
      '{{notification.message}}' +
      '<span class="close" ng-click="close(\'{{notification.id}}\')"></span>' +
      '</div>',
    link: function(scope, element, attrs) {

      //get queue
      scope.queue = NotificationService.getQueue(attrs.id);

      //close notification
      scope.close = function(id){
        NotificationService.removeNotification(id);
      };

    }
  };
});
