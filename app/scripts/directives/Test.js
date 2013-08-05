'use strict';

angular.module('shiftContentApp')
  .directive('Test', function () {
    return {
      template: '<div></div>',
      restrict: 'E',
      link: function postLink(scope, element, attrs) {
        element.text('this is the Test directive');
        window.alert('Baked!');
      }
    };
  });
