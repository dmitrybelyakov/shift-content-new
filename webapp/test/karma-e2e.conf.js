// Karma configuration
// http://karma-runner.github.io/0.10/config/configuration-file.html

module.exports = function(config) {
  config.set({

    // testing framework to use (jasmine/mocha/qunit/...)
    frameworks: ['ng-scenario'],

    // list of files / patterns to load in the browser
    files: [

      //angular libs
      '../components/angular-scenario/angular-scenario.js',

      //e2e tests
      'e2e/**/*.js'
    ],

    urlRoot: '_karma_',
    proxies: {
       '/': 'http://localhost:9002/'
    }


  });
};
