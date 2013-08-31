'use strict';
var LIVERELOAD_PORT = 35729;
var lrSnippet = require('connect-livereload')({ port: LIVERELOAD_PORT });
var proxySnippet = require('grunt-connect-proxy/lib/utils').proxyRequest;

//get config
var yo = require(require('path').resolve('webapp/config/grunt.json'));
yo.routes = require(require('path').resolve('webapp/config/routes.json'));
yo.bake = require(require('path').resolve('webapp/config/bake.json'));

module.exports = function (grunt) {
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  grunt.initConfig({
    yo: yo,
    watch: {
      compass: {
        files: ['<%= yo.app %>/sass/{,*/}*.{scss,sass}'],
        tasks: ['compass:server']
      },
      bake: {
        files: ['<%= yo.app %>/*.html', '<%= yo.app %>/view-partials/*.html'],
        tasks: ['bake']
      },
      livereload: {
        options: {
          livereload: LIVERELOAD_PORT
        },
        files: [
          '<%= yo.app %>/{,*/}/{,*/}*.html',
          '!<%= yo.app %>/{index,main}.html', //handled by baker
          '!<%= yo.app %>/view-partials/*.html', //handled by baker
          '.tmp/{,*/}*.html',
          '.tmp/css/{,*/}*.css',
          '<%= yo.app %>/sass/{,*/}*.css',
          '{.tmp,<%= yo.app %>}/scripts/{,*/}{,*/}*.js',
          '<%= yo.app %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
        ]
      }
    },
    bake: {
      index: {
        options: {},
        files: yo.bake.index
      }
    },
    connect: {
      options: {
        port: 9002,
        hostname: 'localhost' // Change to '0.0.0.0' to access from outside.
      },
      proxies: [{
        context: '/',
        host: '<%= yo.proxyHost %>',
        port: '<%= yo.proxyPort %>',
        https: false,
        changeOrigin: false
      }],
      livereload: {
        options: {
          routes: yo.routes,
          middleware: function (connect, options) {
            return [
              lrSnippet,
              require('connect-conductor').route(options),
              connect.static(options.base),
              proxySnippet
            ];
          }
        }
      },
      test: {
        options: {
          routes: yo.routes,
          middleware: function (connect, options) {
            return [
              require('connect-conductor').route(options),
              connect.static(options.base),
              proxySnippet
            ];
          }
        }
      }
    },
    open: {
      server: {
        url: 'http://localhost:<%= connect.options.port %>' + yo.webPath + '/'
      }
    },
    clean: {
      dist: {
        files: [{
          dot: true,
          src: [
            '.tmp',
            '<%= yo.dist %>/*',
            '!<%= yo.dist %>/.git*',
            '<%= yo.processedDist %>'
          ]
        }]
      },
      server: '.tmp',
      finalize: ['<%= yo.dist %>/modules']
    },
    jshint: {
      options: {
        jshintrc: '<%= yo.app %>/config/.jshintrc'
      },
      all: [
        'Gruntfile.js',
        '<%= yo.app %>/scripts/{,*/}*.js'
      ]
    },
    compass: {
      options: {
        environment: 'development',
        outputStyle: 'expanded',
        relativeAssets: false,
        time: true,
        sassDir: '<%= yo.app %>/sass',
        cssDir: '.tmp/css',
        generatedImagesDir: '.tmp/img/generated',
        imagesDir: '<%= yo.app %>/img',
        javascriptsDir: '<%= yo.app %>/scripts',
        fontsDir: '<%= yo.app %>/sass/fonts',
        importPath: '<%= yo.app %>/components',
        httpImagesPath: yo.webPath + '/img',
        httpGeneratedImagesPath: yo.webPath + '/img/generated',
        httpFontsPath: yo.webPath + '/css/fonts'
      },
      dist: {},
      server: {
        options: {
          debugInfo: true
        }
      }
    },
    rev: {
      dist: {
        files: {
          src: [
            '<%= yo.distTemp %>/scripts/{,*/}*.js',
            '<%= yo.distTemp %>/css/{,*/}*.css',
            '<%= yo.distTemp %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
            '<%= yo.distTemp %>/css/fonts/*'
          ]
        }
      }
    },
    useminPrepare: {
      html: [
        '<%= yo.dist %>/*.html',
        '<%= yo.dist %>/view-partials/*.html'
      ],
      options: {
        dest: '<%= yo.dist %>'
      }
    },
    usemin: {
      html: [
        '<%= yo.dist %>/{,*/}*.{html,phtml}',
        '<%= yo.dist %>/view-partials/{,*/}*.{html,phtml}'
      ],
      css: ['<%= yo.distTemp %>/css/{,*/}*.css'],
      options: {
        dirs: ['<%= yo.dist %>']
      }
    },
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yo.app %>/img',
          src: '{,*/}*.{png,jpg,jpeg}',
          dest: '<%= yo.distTemp %>/img'
        }]
      }
    },
    svgmin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yo.app %>/img',
          src: '{,*/}*.svg',
          dest: '<%= yo.distTemp %>/img'
        }]
      }
    },
    copy: {
      dist: {
        files: [
          {
            expand: true,
            dot: true,
            cwd: '<%= yo.app %>',
            dest: '<%= yo.distTemp %>',
            src: [
              '*.{ico,png,txt}',
              '.htaccess',
              'components/**/*',
              'img/{,*/}*.{gif,webp}'
            ]
          },
          {
            expand: true,
            cwd: '.tmp',
            dest: '<%= yo.distTemp %>',
            src: ['img/generated/*']
          },
          {
            expand: true,
            cwd: '<%= yo.app %>/sass/fonts',
            dest: '<%= yo.distTemp %>/css/fonts',
            src: ['*']
          },
          {
            expand: true,
            cwd: '<%= yo.app %>',
            dest: '<%= yo.dist %>',
            src: [
              'index.html',
              'views/{,*/}*.html',
              'view-partials/{,*/}*.html'
            ]
          },
          {
            expand: true,
            cwd: '<%= yo.processedSrc %>',
            dest: '<%= yo.dist %>/view-partials',
            src: ['{,*/}*.{html,phtml}']
          }
        ]
      }//dist
    },
    rename: {
      scriptsPartial: {
        src: '<%= yo.dist %>/index.html',
        dest: '<%= yo.dist %>/view-partials/scripts.html'
      },
      partials: {
        src: '<%= yo.dist %>/view-partials',
        dest: '<%= yo.processedDist %>'
      },
      dist: {
        src: '<%= yo.distTemp %>',
        dest: '<%= yo.distFull %>'
      },
      views: {
        src: '<%= yo.dist %>/views',
        dest: '<%= yo.distFull %>/views'
      }
    },
    concurrent: {
      server: [
        'compass:server'
      ],
      dist: [
        'compass:dist',
        'imagemin',
        'svgmin'
      ]
    },
    ngmin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yo.distTemp %>/scripts',
          src: '*.js',
          dest: '<%= yo.distTemp %>/scripts'
        }]
      }
    },
    uglify: {
      dist: {
        files: {
          '<%= yo.distTemp %>/scripts/scripts.js': [
            '<%= yo.distTemp %>/scripts/scripts.js'
          ]
        }
      }
    },
    /*
     * Testing
     */
    karma: {
      unit: {
        configFile: '<%= yo.app %>/test/karma.conf.js',
        singleRun: true
      },
      e2e: {
        configFile: '<%= yo.app %>/test/karma-e2e.conf.js',
        singleRun: true
      }
    }
  });

  grunt.registerTask('server', [
    'clean:server',
    'bake:index',
    'concurrent:server',
    'configureProxies',
    'connect:livereload',
    'open',
    'watch'
  ]);

  grunt.registerTask('test-server', [
    'bake:index',
    'configureProxies',
    'connect:test'
  ]);

  grunt.registerTask('test:unit', ['karma:unit']);
  grunt.registerTask('test:e2e', ['test-server', 'karma:e2e']);

  grunt.registerTask('finalize', [
    'rename:scriptsPartial',
    'rename:partials',
    'rename:dist',
    'rename:views',
    'clean:server',
    'clean:finalize'
  ]);


  grunt.registerTask('build', [
    'jshint',
    'test',
    'clean:dist',
    'bake:index', //compose index of templates
    'concurrent:dist', //compile compass to temp and minify images to dist
    'copy:dist', //copy temp images, app scripts & templates to dist
    'useminPrepare', //parse index to find minification instruction for js/css
    'concat', //from usemin
    'ngmin', //prepare scripts in dist
    'cssmin', //copy minified css from temp to dist
    'uglify', //minfy dist scripts per usemin config
    'rev', //rev assets
    'usemin:html', //update templates with revved assets
    'usemin:css', //update css with revved assets
    'finalize'
  ]);

  grunt.registerTask('default', 'build');
};
