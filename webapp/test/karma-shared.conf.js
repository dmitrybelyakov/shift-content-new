
// Karma configuration
// http://karma-runner.github.io/0.10/config/configuration-file.html


var shared = function(config) {
  config.set({

    // base path, that will be used to resolve files and exclude
    basePath: '',

    // testing framework to use (jasmine/mocha/qunit/...)
    frameworks: ['jasmine'],

    // list of files / patterns to exclude
    exclude: [],

    // web server port
    port: 8080,

    // level of logging
    // possible values: LOG_DISABLE || LOG_ERROR || LOG_WARN || LOG_INFO || LOG_DEBUG
    logLevel: config.LOG_INFO,

//    // enable / disable watching file and executing tests whenever any file changes
//    autoWatch: false,

    // Start these browsers, currently available:
    // - Chrome
    // - ChromeCanary
    // - Firefox
    // - Opera
    // - Safari (only Mac)
    // - PhantomJS
    // - IE (only Windows)
    browsers: ['Chrome'],

    // Continuous Integration mode
    // if true, it capture browsers, run tests and exit
    singleRun: false,

    // Use colors
    colors: true

  });
};

shared.files = [

  //angular libs
  '../components/angular/angular.js',
  '../components/angular-route/angular-route.js',
  '../components/angular-animate/angular-animate.js',

  //application code
  '../scripts/*.js',
  '../scripts/**/*.js'
];

module.exports = shared;
