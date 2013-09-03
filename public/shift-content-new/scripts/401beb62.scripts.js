"use strict";var app=angular.module("shiftContentApp",["ngRoute","ngAnimate","angular-cache"]);app.constant("viewsBase","/modules/shift-content-new/views"),app.config(["$locationProvider","$routeProvider","viewsBase",function(a,b,c){a.html5Mode(!0).hashPrefix("!");var d=b,e=c;d.otherwise({redirectTo:"/"}),d.when("/",{controller:"MainCtrl",templateUrl:e+"/main.html"}),d.when("/index/",{redirectTo:"/"}),d.when("/manage/:contentId/",{controller:"MainCtrl",templateUrl:e+"/another.html"}),d.when("/feeds/",{controller:"MainCtrl",templateUrl:e+"/manage-feeds.html"}),d.when("/field-types/",{controller:"MainCtrl",templateUrl:e+"/field-types.html"}),d.when("/filter-types/",{controller:"MainCtrl",templateUrl:e+"/filter-types.html"}),d.when("/validator-types/",{controller:"MainCtrl",templateUrl:e+"/validator-types.html"})}]);var app=angular.module("shiftContentApp");app.controller("ContentTypeCtrl",["$scope","type",function(a,b){var c=window._;a.type=b,a.typeMemory=c.clone(b),a.typeFormVisible=!1,a.typeFormProgress=!1,a.showTypeForm=function(){a.typeFormVisible=!0},a.hideTypeForm=function(){a.typeFormVisible=!1,a.type.name=a.typeMemory.name,a.type.description=a.typeMemory.description},a.fieldFormVisible=!1,a.fieldFormProgress=!1}]);var app=angular.module("shiftContentApp");app.controller("ContentTypesCtrl",["$scope","$location","types","TypeRepository","NotificationService",function(a,b,c,d,e){var f=window._,g=d,h=e;a.types=f.sortBy(c,function(a){return a.name}),a.editType=function(a){b.path(b.path()+a+"/")},a.deleteType=function(b){var c=g.delete(b),d=!1;c.catch(function(a){404===a.status&&(d=!0)}),c.error(function(a){var b="Server error";d&&(b+=": Not found"),a.content&&(b+=": "+a.content),h.send("default","error",b)}),c.success(function(){a.types=f.reject(a.types,function(a){return a.id===b.id}),h.growl("Content type deleted")})},a.newTypeForm={},a.formVisible=!1,a.formProgress=!1,a.newType={name:void 0,description:void 0},a.showForm=function(){a.formVisible=!0},a.hideForm=function(){a.formVisible=!1,a.formProgress=!1,a.newType.name=void 0,a.newType.description=void 0,a.newTypeForm.shift.clearBackendErrors(),a.newTypeForm.shift.clearSubmitted(),a.newTypeForm.$setPristine()},a.createType=function(){if(!a.newTypeForm.$invalid){a.formProgress=!0;var b,c=g.create(a.newType);c.catch(function(a){409===a.status&&(b=a.data)}),c.error(function(c){if(a.formProgress=!1,b)a.newTypeForm.shift.setBackendErrors(b);else{var d="Server error";c.content&&(d+=": "+c.content),h.send("default","error",d)}}),c.success(function(b){a.types.push(b),a.types=f.sortBy(a.types,function(a){return a.name}),a.hideForm(),h.growl("Added content type")})}}}]);var app=angular.module("shiftContentApp");app.factory("TypeRepository",["$http","$angularCacheFactory",function(a,b){b("contentTypes",{maxAge:9e4,cacheFlushInterval:6e5,aggressiveDelete:!0,storageMode:"localStorage"});var c=b.get("contentTypes");c.removeAll();var d=window._,e="/api/content/types/",f={};return f.get=function(b){return b=b.toString(),c.get(b)?c.get(b):a.get(e+b+"/").success(function(a){c.put(b,a)}).then(function(a){return a.data})},f.query=function(){return c.get("all")?c.get("all"):a.get(e).then(function(a){return c.put("all",a.data),d.each(a.data,function(a){c.put(a.id.toString(),a)}),a.data})},f.create=function(b){return a.post(e,b).success(function(a){c.put(a.id.toString(),a),c.get("all").push(a)})},f.delete=function(b){return a.delete(e+b.id+"/").success(function(){c.remove(b.id),c.put("all",d.reject(c.get("all"),function(a){return a.id===b.id}))})},f}]);var app=angular.module("shiftContentApp");app.config(["$routeProvider","viewsBase",function(a,b){var c=a,d=b;c.when("/types/",{controller:"ContentTypesCtrl",templateUrl:d+"/content-types/list.html",resolve:{types:["TypeRepository",function(a){return a.query()}]}}),c.when("/types/:id/",{controller:"ContentTypeCtrl",templateUrl:d+"/content-types/type.html",resolve:{type:["TypeRepository","$route",function(a,b){return a.get(b.current.params.id)}]}})}]);var app=angular.module("shiftContentApp");app.animation(".exampleAnimation",function(){return{addClass:function(a,b,c){var d=100,e=a.height(),f=function(){a.height(e),c()};"ng-hide"===b?a.animate({opacity:0,height:0},d,f):c()},removeClass:function(a,b,c){var d=100,e=a.height();a.css("height",e);var f=function(){a.height("auto"),c()};"ng-hide"===b?(a.removeClass("ng-hide"),a.css("opacity",0),a.height(0),a.animate({opacity:1,height:e},d,f)):c()}}});var app=angular.module("shiftContentApp");app.directive("shiftForm",function(){return{restrict:"A",require:"form",priority:1e3,link:function(a,b,c,d){d.shift={},d.shift.attemptedSubmission=!1,d.shift.backendErrors={},b.bind("submit",function(){d.shift.setSubmitted()}),d.shift.isSubmitted=function(){return d.shift.attemptedSubmission},d.shift.setSubmitted=function(){d.shift.attemptedSubmission=!0},d.shift.clearSubmitted=function(){d.shift.attemptedSubmission=!1},d.shift.addBackendError=function(a,b,c){if(a&&c&&b){d.shift.backendErrors[a]||(d.shift.backendErrors[a]=[]);var e={key:b,message:c},f=!1;for(var g in d.shift.backendErrors[a])d.shift.backendErrors[a][g].key===b&&(f=!0,d.shift.backendErrors[a][g]=e);f||d.shift.backendErrors[a].push(e)}},d.shift.setBackendErrors=function(a){for(var b in a)if(a.hasOwnProperty(b)){var c=a[b];for(var e in c)if(c.hasOwnProperty(e)){var f=c[e];d.shift.addBackendError(b,e,f)}}},d.shift.clearBackendErrors=function(){d.shift.backendErrors={}},d.shift.getBackendErrors=function(a){return a?a&&d.shift.backendErrors[a]?d.shift.backendErrors[a]:{}:d.shift.backendErrors},d.shift.hasBackendErrors=function(a){if(a)return d.shift.backendErrors[a]&&d.shift.backendErrors[a].length>0?!0:!1;var b=0;for(var c in d.shift.backendErrors)d.shift.backendErrors.hasOwnProperty(c)&&b++;return b>0?!0:!1},d.shift.fieldValid=function(a){if(!a||!d[a])return!1;var b=d[a];return d.shift.hasBackendErrors(a)||b.$invalid&&!b.$pristine?!1:!0},d.shift.fieldErrors=function(a){return d.shift.isSubmitted()?d.shift.hasBackendErrors(a)||d[a].$invalid?!0:!1:!1}}}});var app=angular.module("shiftContentApp");app.factory("NavigationService",function(){var a=[{title:"Manage content",items:[{label:"Static page",href:"manage/11/"},{label:"Attachment",href:"manage/12/"},{label:"Shop Item",href:"manage/13/"}]},{title:"Manage content",items:[{label:"Feed collections",href:"feeds/"}]},{title:"Content settings",items:[{label:"Content types",href:"types/"},{label:"Field types",href:"field-types/"},{label:"Filter types",href:"filter-types/"},{label:"Validator types",href:"validator-types/"}]}];return a});var app=angular.module("shiftContentApp");app.directive("shiftBreadcrumbs",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/directives/breadcrumbs.html"}});var app=angular.module("shiftContentApp");app.directive("shiftNavigation",["NavigationService",function(a){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/directives/navigation.html",controller:["$scope","$location",function(b,c){var d=a,e=function(){var a=c.path().substring(1);for(var b in d)for(var e in d[b].items)d[b].items[e].active=a===d[b].items[e].href?!0:!1};e(),b.$on("$routeChangeStart",function(){e()}),b.navigation=d}]}}]);var app=angular.module("shiftContentApp");app.service("NotificationService",["$timeout",function(a){var b={};b.queues=[],b.growls=[];var c=2e3;return b.queName=function(a){return a.toString().replace(/[^A-Z0-9]/gi,"")},b.send=function(c,d,e,f){c=b.queName(c),b.queues[c]||(b.queues[c]=[]),f?-1!==f.indexOf("ms")?f=parseFloat(f):-1!==f.indexOf("s")&&(f=parseFloat(f)):f=5e3;var g=Math.random().toString(36).slice(2);b.queues[c].push({id:g,queue:c,type:d,message:e,timeout:f}),"infinite"!==f&&a(function(){b.removeNotification(g)},f)},b.getQueue=function(a){return a=b.queName(a),b.queues[a]||(b.queues[a]=[]),b.queues[a]},b.getAllQueues=function(){return b.queues},b.removeNotification=function(a){for(var c in b.queues)if(b.queues.hasOwnProperty(c))for(var d in b.queues[c])b.queues[c].hasOwnProperty(d)&&b.queues[c][d].id===a&&b.queues[c].splice(d,1)},b.growl=function(d){var e=Math.random().toString(36).slice(2);b.growls.push({id:e,message:d}),a(function(){b.removeGrowl(e)},c)},b.removeGrowl=function(a){for(var c in b.growls)b.growls.hasOwnProperty(c)&&b.growls[c].id===a&&b.growls.splice(c,1)},b.getGrowls=function(){return b.growls},b}]);var app=angular.module("shiftContentApp");app.directive("shiftGrowl",["NotificationService",function(a){return{restrict:"A",scope:{},templateUrl:"/modules/shift-content-new/views/directives/notification.html",link:function(b){b.growls=a.getGrowls(),b.close=function(b){a.removeGrowl(b)}}}}]);var app=angular.module("shiftContentApp");app.directive("shiftNotifications",["NotificationService",function(a){return{restrict:"A",scope:{},template:'<div class="notification {{notification.type}}" ng-repeat="notification in queue">{{notification.message}}<span class="close" ng-click="close(\'{{notification.id}}\')"></span></div>',link:function(b,c,d){b.queue=a.getQueue(d.id),b.close=function(b){a.removeNotification(b)}}}}]);var app=angular.module("shiftContentApp");app.directive("shiftRoutePreloader",["$rootScope","$timeout","$location",function(a,b,c){return{templateUrl:"/modules/shift-content-new/views/directives/view-preloader.html",restrict:"EA",controller:["$scope",function(a){a.message="Loading..."}],link:function(d,e){var f=angular.element,g=!1;e=e.find(".view-preloader"),e.hide(),a.$on("$routeChangeStart",function(){g=!0,b(function(){if(g){var a=f("td.page");e.width(a.width()),e.css("left",f("td.navigation").width()),e.show()}},50)}),a.$on("$routeChangeSuccess",function(){g=!1,e.hide()});var h=function(a,b){var c=[],d=(a||"").split(":");for(var e in d){var f=d[e];if(0===parseInt(e,10))c.push(f);else{var g=f.match(/(\w+)(.*)/),h=g[1];c.push(b[h]),c.push(g[2]||""),delete b[h]}}return c.join("")},i=d.message;a.$on("$routeChangeError",function(a,f,j,k){var l="Loading failed";k&&(l=l+": "+k.data.content),d.message=l,e.addClass("error"),b(function(){g=!1,e.hide(),e.removeClass("error"),d.message=i;var a="/";j&&(a=h(j.originalPath,j.params)),c.path(a)},4e3)})}}}]);var app=angular.module("shiftContentApp");app.controller("MainCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]);