'use strict';
var app = angular.module('shiftContentApp');


app.factory('NavigationService', function () {

  //we would actually use the http and promise from here
  var navigation = [
    {
      title: 'Manage content',
      items: [
        {label: 'Static page', href: 'manage/11/'},
        {label: 'Attachment', href: 'manage/12/'},
        {label: 'Shop Item', href: 'manage/13/'}
      ]
    },
    {
      title: 'Manage content',
      items: [
        {label: 'Feed collections', href: 'feeds/'}
      ]
    },
    {
      title: 'Content settings',
      items: [
        {label: 'Content types', href: 'types/'},
        {label: 'Field types', href: 'field-types/'},
        {label: 'Filter types', href: 'filter-types/'},
        {label: 'Validator types', href: 'validator-types/'}
      ]
    }
  ];


  return navigation;

});
