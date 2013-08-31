"use strict";var app=angular.module("shiftContentApp",["ngRoute","ngAnimate"]);app.config(["$locationProvider","$routeProvider",function(a,b){a.html5Mode(!0).hashPrefix("!");var c=b,d="/modules/shift-content-new/views";c.otherwise({redirectTo:"/"}),c.when("/",{controller:"MainCtrl",templateUrl:d+"/main.html"}),c.when("/index/",{redirectTo:"/"}),c.when("/another/",{controller:"MainCtrl",templateUrl:d+"/another.html"}),c.when("/manage/:contentId/",{controller:"MainCtrl",templateUrl:d+"/another.html"}),c.when("/feeds/",{controller:"Queue",templateUrl:d+"/manage-feeds.html"}),c.when("/types/",{controller:"ContentTypes",templateUrl:d+"/content-types/list.html",resolve:{types:["ContentTypes",function(a){return a.query()}]}}),c.when("/types/:id/",{controller:"ContentType",templateUrl:d+"/content-types/type.html",resolve:{type:["ContentTypes","$route",function(a,b){return a.get(b.current.params.id)}]}}),c.when("/field-types/",{controller:"MainCtrl",templateUrl:d+"/field-types.html"}),c.when("/filter-types/",{controller:"MainCtrl",templateUrl:d+"/filter-types.html"}),c.when("/validator-types/",{controller:"MainCtrl",templateUrl:d+"/validator-types.html"})}]);var app=angular.module("shiftContentApp");app.controller("MainCtrl",["$scope",function(a){a.awesomeThings=["HTML5 Boilerplate","AngularJS","Karma"]}]);var app=angular.module("shiftContentApp");app.controller("ContentTypes",["$scope","$location","types","ContentTypes","NotificationService",function(a,b,c,d,e){var f=window._,g=d,h=e;a.types=f.sortBy(c,function(a){return a.name}),a.editType=function(a){b.path(b.path()+a+"/")},a.deleteType=function(b){var c=g.delete(b),d=!1;c.catch(function(a){404===a.status&&(d=!0)}),c.error(function(a){var b="Server error";d&&(b+=": Not found"),a.content&&(b+=": "+a.content),h.send("default","error",b)}),c.success(function(){a.types=f.reject(a.types,function(a){return a.id===b.id}),h.growl("Content type deleted")})},a.newTypeForm={},a.formVisible=!1,a.formProgress=!1,a.newType={name:void 0,description:void 0},a.showForm=function(){a.formVisible=!0},a.hideForm=function(){a.formVisible=!1,a.formProgress=!1,a.newType.name=void 0,a.newType.description=void 0,a.newTypeForm.shift.clearBackendErrors(),a.newTypeForm.shift.clearSubmitted(),a.newTypeForm.$setPristine()},a.createType=function(){if(!a.newTypeForm.$invalid){a.formProgress=!0;var b,c=g.create(a.newType);c.catch(function(a){409===a.status&&(b=a.data)}),c.error(function(c){if(a.formProgress=!1,b)a.newTypeForm.shift.setBackendErrors(b);else{var d="Server error";c.content&&(d+=": "+c.content),h.send("default","error",d)}}),c.success(function(b){a.types.push(b),a.types=f.sortBy(a.types,function(a){return a.name}),a.hideForm(),h.growl("Added content type")})}}}]);var app=angular.module("shiftContentApp");app.controller("ContentType",["$scope","type",function(a,b){a.type=b}]);var app=angular.module("shiftContentApp");app.directive("shiftNavigation",["NavigationService",function(a){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/directives/navigation.html",controller:["$scope","$location",function(b,c){var d=a,e=function(){var a=c.path().substring(1);for(var b in d)for(var e in d[b].items)d[b].items[e].active=a===d[b].items[e].href?!0:!1};e(),b.$on("$routeChangeStart",function(){e()}),b.navigation=d}]}}]);var app=angular.module("shiftContentApp");app.directive("shiftBreadcrumbs",function(){return{scope:{},restrict:"EA",templateUrl:"/modules/shift-content-new/views/directives/breadcrumbs.html"}});var app=angular.module("shiftContentApp");app.directive("routePreloader",["$rootScope","$timeout","$location",function(a,b,c){return{templateUrl:"/modules/shift-content-new/views/directives/view-preloader.html",restrict:"EA",controller:["$scope",function(a){a.message="Loading..."}],link:function(d,e){var f=angular.element,g=!1;e=e.find(".view-preloader"),e.hide(),a.$on("$routeChangeStart",function(){g=!0,b(function(){if(g){var a=f("td.page");e.width(a.width()),e.css("left",f("td.navigation").width()),e.show()}},50)}),a.$on("$routeChangeSuccess",function(){g=!1,e.hide()});var h=function(a,b){var c=[],d=(a||"").split(":");for(var e in d){var f=d[e];if(0===parseInt(e,10))c.push(f);else{var g=f.match(/(\w+)(.*)/),h=g[1];c.push(b[h]),c.push(g[2]||""),delete b[h]}}return c.join("")},i=d.message;a.$on("$routeChangeError",function(a,f,j,k){var l="Loading failed";k&&(l=l+": "+k.data.content),d.message=l,e.addClass("error"),b(function(){g=!1,e.hide(),e.removeClass("error"),d.message=i;var a="/";j&&(a=h(j.originalPath,j.params)),c.path(a)},4e3)})}}}]);var app=angular.module("shiftContentApp");app.directive("shiftForm",function(){return{restrict:"A",require:"form",priority:1e3,link:function(a,b,c,d){d.shift={},d.shift.attemptedSubmission=!1,d.shift.backendErrors={},b.bind("submit",function(){d.shift.setSubmitted()}),d.shift.isSubmitted=function(){return d.shift.attemptedSubmission},d.shift.setSubmitted=function(){d.shift.attemptedSubmission=!0},d.shift.clearSubmitted=function(){d.shift.attemptedSubmission=!1},d.shift.addBackendError=function(a,b,c){if(a&&c&&b){d.shift.backendErrors[a]||(d.shift.backendErrors[a]=[]);var e={key:b,message:c},f=!1;for(var g in d.shift.backendErrors[a])d.shift.backendErrors[a][g].key===b&&(f=!0,d.shift.backendErrors[a][g]=e);f||d.shift.backendErrors[a].push(e)}},d.shift.setBackendErrors=function(a){for(var b in a)if(a.hasOwnProperty(b)){var c=a[b];for(var e in c)if(c.hasOwnProperty(e)){var f=c[e];d.shift.addBackendError(b,e,f)}}},d.shift.clearBackendErrors=function(){d.shift.backendErrors={}},d.shift.getBackendErrors=function(a){return a?a&&d.shift.backendErrors[a]?d.shift.backendErrors[a]:{}:d.shift.backendErrors},d.shift.hasBackendErrors=function(a){if(a)return d.shift.backendErrors[a]&&d.shift.backendErrors[a].length>0?!0:!1;var b=0;for(var c in d.shift.backendErrors)d.shift.backendErrors.hasOwnProperty(c)&&b++;return b>0?!0:!1},d.shift.fieldValid=function(a){if(!a||!d[a])return!1;var b=d[a];return d.shift.hasBackendErrors(a)||b.$invalid&&!b.$pristine?!1:!0},d.shift.fieldErrors=function(a){return d.shift.isSubmitted()?d.shift.hasBackendErrors(a)||d[a].$invalid?!0:!1:!1}}}}),angular.module("shiftContentApp").directive("shiftInitialHeight",function(){return{restrict:"A",link:function(a,b){b.css("height",b.height())}}});var app=angular.module("shiftContentApp");app.factory("NavigationService",function(){var a=[{title:"Manage content",items:[{label:"Static page",href:"manage/11/"},{label:"Attachment",href:"manage/12/"},{label:"Shop Item",href:"manage/13/"}]},{title:"Manage content",items:[{label:"Feed collections",href:"feeds/"}]},{title:"Content settings",items:[{label:"Content types",href:"types/"},{label:"Field types",href:"field-types/"},{label:"Filter types",href:"filter-types/"},{label:"Validator types",href:"validator-types/"}]}];return a});var app=angular.module("shiftContentApp");app.service("NotificationService",["$timeout",function(a){var b={};b.queues=[],b.growls=[];var c=2e3;return b.queName=function(a){return a.toString().replace(/[^A-Z0-9]/gi,"")},b.send=function(c,d,e,f){c=b.queName(c),b.queues[c]||(b.queues[c]=[]),f?-1!==f.indexOf("ms")?f=parseFloat(f):-1!==f.indexOf("s")&&(f=parseFloat(f)):f=5e3;var g=Math.random().toString(36).slice(2);b.queues[c].push({id:g,queue:c,type:d,message:e,timeout:f}),"infinite"!==f&&a(function(){b.removeNotification(g)},f)},b.getQueue=function(a){return a=b.queName(a),b.queues[a]||(b.queues[a]=[]),b.queues[a]},b.getAllQueues=function(){return b.queues},b.removeNotification=function(a){for(var c in b.queues)if(b.queues.hasOwnProperty(c))for(var d in b.queues[c])b.queues[c].hasOwnProperty(d)&&b.queues[c][d].id===a&&b.queues[c].splice(d,1)},b.growl=function(d){var e=Math.random().toString(36).slice(2);b.growls.push({id:e,message:d}),a(function(){b.removeGrowl(e)},c)},b.removeGrowl=function(a){for(var c in b.growls)b.growls.hasOwnProperty(c)&&b.growls[c].id===a&&b.growls.splice(c,1)},b.getGrowls=function(){return b.growls},b}]);var app=angular.module("shiftContentApp");app.factory("MultiTypeLoader",["ContentTypeRepository","$q",function(a,b){return function(){var c=b.defer();return a.query(function(a){c.resolve(a)},function(){c.reject("Unable to load from backend")}),c.promise}}]),app.factory("TypeLoader",["ContentTypeRepository","$route","$q",function(a,b,c){return function(){var d=c.defer(),e=b.current.params.id;return a.get({id:e},function(a){d.resolve(a)},function(){d.reject("Unable to fetch recipe: "+e)}),d.promise}}]);var app=angular.module("shiftContentApp");app.factory("ContentTypeRepository",["$resource",function(a){var b="/api/content/types/:id/",c={id:"@id"},d={get:{method:"GET",isArray:!1},save:{method:"POST",isArray:!0}};return a(b,c,d)}]);var app=angular.module("shiftContentApp");app.factory("ContentTypes",["$http",function(a){var b="/api/content/types/",c={};return c.get=function(c){return a.get(b+c+"/").then(function(a){return a.data})},c.query=function(){return a.get(b).then(function(a){return a.data})},c.create=function(c){return a.post(b,c)},c.delete=function(c){return a.delete(b+c.id+"/")},c}]);var app=angular.module("shiftContentApp");app.controller("Queue",["$scope","NotificationService",function(a,b){var c=b;a.addMessage=function(){c.notify(123,"message","Example message...")},a.addMessage2=function(){c.notify("somename","error","Example message...","infinite")},a.growl=function(){c.growl("Rrrrr...")}}]);var app=angular.module("shiftContentApp");app.directive("shiftNotifications",["NotificationService",function(a){return{restrict:"A",scope:{},template:'<div class="notification {{notification.type}}" ng-repeat="notification in queue">{{notification.message}}<span class="close" ng-click="close(\'{{notification.id}}\')"></span></div>',link:function(b,c,d){b.queue=a.getQueue(d.id),b.close=function(b){a.removeNotification(b)}}}}]);var app=angular.module("shiftContentApp");app.directive("shiftGrowl",["NotificationService",function(a){return{restrict:"A",scope:{},templateUrl:"/modules/shift-content-new/views/directives/notification.html",link:function(b){b.growls=a.getGrowls(),b.close=function(b){a.removeGrowl(b)}}}}]);