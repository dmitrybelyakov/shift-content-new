'use strict';
/**
 * Inject height
 * This directive is used to get element height and inject it as explicit css
 * property.
 *
 * !!! This is required to properly do animations on height. !!!
 */
angular.module('shiftContentApp')
  .directive('shiftInitialHeight', function () {
    return {
      restrict: 'A',
      link: function postLink(scope, element) {
        element.css('height', element.height());
      }
    };
  });
