'use strict';
var app = angular.module('shiftContentApp');

/**
 * Route preloader
 * Used to display feedback in overlay while routes are being resolved.
 * Only appears after short timeout when a real backend request is being made.
 */
app.directive('shiftRoutePreloader', function($rootScope, $timeout, $location) {
    return {
      templateUrl: '/modules/shift-content-new/views' +
        '/directives/view-preloader.html',
      restrict: 'EA',
      controller: function($scope) {
        $scope.message = 'Loading...';
      },
      link: function postLink(scope, element) {
        var $ = angular.element;
        var shouldBeVisible = false;

        element = element.find('.view-preloader');
        element.hide();

        //display loader with timeout
        $rootScope.$on('$routeChangeStart', function(){

          shouldBeVisible = true;
          $timeout(function(){

              //may be changed during timeout
              if(!shouldBeVisible) {
                return;
              }

              var content = $('td.page');
              element.width(content.width());
              element.css('left', $('td.navigation').width());
              element.show();
            },
            50
          );

        });

        //hide on success
        $rootScope.$on('$routeChangeSuccess', function(){
          shouldBeVisible = false;
          element.hide();
        });

        //interpolate url (this is from route provider)
        var interpolate = function (string, params) {
          var result = [];
          var split = (string || '').split(':');
          for(var s in split) {
            var segment = split[s];
            if(0 === parseInt(s, 10)) {
              result.push(segment);
            } else {
              var segmentMatch = segment.match(/(\w+)(.*)/);
              var key = segmentMatch[1];
              result.push(params[key]);
              result.push(segmentMatch[2] || '');
              delete params[key];
            }
          }

          return result.join('');
        };


        //resolve error
        var loading = scope.message;
        $rootScope.$on('$routeChangeError', function(
          event, current, previous, error){

          //get backend error
          var message = 'Loading failed';
          if(error) {
            message = message + ': ' + error.data.content;
          }

          //set error state
          scope.message = message;
          element.addClass('error');

          //hide after timeout & redirect back
          $timeout(function(){
            shouldBeVisible = false;
            element.hide();

            element.removeClass('error');
            scope.message = loading;

            //redirect back
            var back = '/';
            if(previous) {

              back = interpolate(previous.originalPath, previous.params);
            }
            $location.path(back);
          }, 4000);


        }); //resolve error

      } //link

    };
  });
