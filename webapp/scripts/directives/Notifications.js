'use strict';

var app = angular.module('shiftContentApp');
app.directive('shiftNotifications', function (NotificationService, $timeout) {
  return {
    restrict: 'A',
    templateUrl:'/modules/shift-content-new/views/directives/notification.html',
    scope: {},
    controller: function($scope){

      //initially empty
      $scope.queue = [];

      //close notification
      $scope.close = function(id){
        NotificationService.removeNotification(id);
      };

    },
    link: function(scope, element, attrs) {

      $timeout(function(){
        scope.queue = NotificationService.getQueue(attrs.id);
      },2000);


    }
  };
});
