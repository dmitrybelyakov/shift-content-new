var shared = require('./karma-shared.conf');
module.exports = function(config) {

  shared(config);
  config.files = [

      //angular libs
      '../components/angular/angular.js',
      '../components/angular-route/angular-route.js',
      '../components/angular-animate/angular-animate.js',
      '../components/angular-mocks/angular-mocks.js',

      //application code
      '../scripts/*.js',
      '../scripts/**/*.js',

      //tests
      'mock/**/*.js',
      'spec/**/*.js'
    ];
};
