var shared = require('./karma-shared.conf');
module.exports = function(config) {

  //used shared config
  shared(config);

  //add unit test specific files
  config.files = [

    //angular libs
    '../components/angular/angular.js',
    '../components/angular-route/angular-route.js',
    '../components/angular-animate/angular-animate.js',
    '../components/angular-mocks/angular-mocks.js',

    //add unit tests
    'mock/**/*.js',
    'spec/**/*.js'
  ];
};
