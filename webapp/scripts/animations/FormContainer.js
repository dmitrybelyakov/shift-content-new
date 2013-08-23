'use strict';
var app = angular.module('shiftContentApp');


/**
 * Form container animation
 * Sets up effects to show and hide form containers.
 *
 * Initial height
 * NOTE: height must be initially set otherwise we will not be able to properly
 * animate the first time. Use directive for that.
 */
app.animation('.formContainer', function() {
  return {

    //hide animation
    addClass : function(element, className, done) {
      var duration = 100;
      var currentHeight = element.height();
      var complete = function(){
        element.height(currentHeight);
        done();
      };

      if(className === 'ng-hide') {
        element.animate({
          opacity:0,
          height: 0
        }, duration, complete);
      }
      else {
        done();
      }
    },

    //show animation
    removeClass : function(element, className, done) {
      var duration = 100;
      var currentHeight = element.height();
      element.css('height', currentHeight);
      var complete = function(){
        element.height('auto');
        done();
      };

      if(className === 'ng-hide') {

        //remove it early so you can animate on it since
        element.removeClass('ng-hide');

        //animate from this
        element.css('opacity',0);
        element.height(0);

        //to this
        element.animate({
          opacity:1,
          height: currentHeight
        }, duration, complete);
      }
      else {
        done();
      }
    }
  };
});