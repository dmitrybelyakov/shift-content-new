'use strict';
var app = angular.module('shiftContentApp');

app.directive('shiftGrowl', function (NotificationService) {
    return {
      restrict: 'A',
      scope: {},
      templateUrl: '/modules/shift-content-new/views/directives/notification.html',
      link: function postLink(scope) {
        scope.growls = NotificationService.getGrowls();
        scope.close = function(id){
          NotificationService.removeGrowl(id);
        };
      } //link

    };
  });
