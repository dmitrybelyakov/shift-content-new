'use strict';

var app = angular.module('shiftContentApp');
app.directive('viewPreloader', function($rootScope, $timeout) {
    return {
      templateUrl: '/modules/shift-content-new/views' +
        '/directives/view-preloader.html',
      restrict: 'EA',
      link: function postLink(scope, element) {

        var $ = angular.element;
        element = element.find('.view-preloader');
        element.hide();

        var padding = 20;
        var content = $('td.page');
        var shouldBeVisible = false;
        var fadeOut = false;

        element.offset({
          top: padding,
          left: content.offset().left
        });

        element.width(content.width() - (padding * 2));
        element.height(content.height() - (padding * 2));

        //display preloader with timeout
        $rootScope.$on('$routeChangeStart', function(){

          shouldBeVisible = true;
          $timeout(function(){
              if(!shouldBeVisible) {
                return;
              }
              element.show();
              fadeOut = true;

            },
            300
          );

        });

        $rootScope.$on('$routeChangeSuccess', function(){
          if(fadeOut) {
            element.addClass('hide');
            $timeout(function(){
              shouldBeVisible = false;
              fadeOut = false;
              element.removeClass('hide');
              element.hide();
            }, 200);

          } else {
            shouldBeVisible = false;
            fadeOut = false;
            element.hide();
          }
        });


      }
    };
  });
