'use strict';

var app = angular.module('shiftContentApp');
app.directive('shiftNotifications', function (NotificationService, $timeout) {
    return {
      restrict: 'A',
      template: '<div class="notification" ng-repeat="i in queue">{{queueId}}</div>',
      scope: {},
      controller: function($scope){
      },
      link: function(scope, element, attrs) {

        scope.message = 'Any shit';
        scope.queue = [1,2,3,4,5];
        scope.queueId = attrs.id;


      }
    };
  });
