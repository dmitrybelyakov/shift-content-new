var shared = require('./karma-shared.conf');
module.exports = function(config) {

  //used shared config
  shared(config);

  config.set({

    // testing framework to use (jasmine/mocha/qunit/...)
    frameworks: ['ng-scenario'],

    // list of files / patterns to load in the browser
    files: ['e2e/**/*.js'],

    urlRoot: '_karma_',
    proxies: {
       '/': 'http://localhost:9002/'
    },
    autoWatch: true,
    singleRun: false

  });
};
