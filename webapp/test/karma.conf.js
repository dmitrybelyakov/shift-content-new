var shared = require('./karma-shared.conf');
module.exports = function(config) {

  //used shared config
  shared(config);

  //add unit test specific files
  config.files = shared.files.concat([

    //add angular mocks
    '../components/angular-mocks/angular-mocks.js',

    //add unit tests
    'mock/**/*.js',
    'spec/**/*.js'
  ]);
};
