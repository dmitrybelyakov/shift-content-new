'use strict';
var app = angular.module('shiftContentApp');


/**
 * Shift form
 * This directive adds functionality to standard ngForm to track submit events
 * and handle backend errors via convenient accessors.
 */
app.directive('shiftForm', function () {
  return {
    restrict: 'A',
    require: 'form',
    priority: 1000, //before ngSubmit
    link: function postLink(scope, element, attrs, form) {

      //check container
      if(!form.shift) {
        form.shift = {};
      }

      /*
       * Handle submit event
       */

      form.shift.attemptedSubmission = false;
      form.shift.backendErrors = {};

      //bind to act on submit
      element.bind('submit', function(){
        form.shift.setSubmitted();
//
//        console.info('IS FORM VALID');
//        console.info(form.$invalid);
//        console.info(form.$dirty);
//
//        if(form.$invalid || form.$valid && form.$pristine) {
//          console.info('cancelling submit');
//          return false;
//        }
//
        form.shift.clearBackendErrors();
      });

      form.shift.isSubmitted = function(){
        return form.shift.attemptedSubmission;
      };
      form.shift.setSubmitted = function(){
        form.shift.attemptedSubmission = true;
      };
      form.shift.clearSubmitted = function(){
        form.shift.attemptedSubmission = false;
      };

      /*
       * Handle backend errors
       */

      //add single field error
      form.shift.addBackendError = function(field, errorKey, message) {
        if(!field || !message || !errorKey) {
          return;
        }

        if(!form.shift.backendErrors[field]) {
          form.shift.backendErrors[field] = [];
        }
        form.shift.backendErrors[field].push({key: errorKey, message: message});
      };

      //set errors all at once
      form.shift.setBackendErrors = function(errors) {
        for(var field in errors) {
          if(errors.hasOwnProperty(field)) {
            var fieldErrors = errors[field];
            for(var errorKey in fieldErrors) {
              if(fieldErrors.hasOwnProperty(errorKey)) {
                var message = fieldErrors[errorKey];
                form.shift.addBackendError(field, errorKey, message);
              }
            }
          }
        }
      };

      //clear backend errors
      form.shift.clearBackendErrors = function(){
        form.shift.backendErrors = {};
      };

      //get errors for field or all at once
      form.shift.getBackendErrors = function(field) {
        if(!field) {
          return form.shift.backendErrors;
        } else if (field && form.shift.backendErrors[field]) {
          return form.shift.backendErrors[field];
        } else {
          return {}
        }
      };


      //checks if form or field has backend errors
      form.shift.hasBackendErrors = function(field) {

        if(!field) {

          //check for form
          var size = 0;
          for(var key in form.shift.backendErrors) {
            if(form.shift.backendErrors.hasOwnProperty(key)) {
              size++;
            };
          }

          if(size > 0) {
            return true;
          } else {
            return false;
          }

        } else {

          //check for field
          if(form.shift.backendErrors[field] &&
            form.shift.backendErrors[field].length > 0) {
            return true;
          } else {
            return false;
          }
        }
      }; //has errors


      //check if field has backend errors OR has frontend errors & is dirty
      form.shift.fieldValid = function(name){
        if(!name || !form[name]) {
          return false;
        }

        var f = form['name'];
        if(form.shift.hasBackendErrors(name) || (f.$invalid && !f.$pristine)) {
          return false;
        }

        return true;
      }

      //returns true if field has errors and form was submitted
      form.shift.fieldErrors = function(name){

        if(!form.shift.isSubmitted()) {
          return false;
        }

        if((form.shift.hasBackendErrors(name) || form[name].$invalid)) {
          return true;
        }

        return false;
      }



    }
  };
});
