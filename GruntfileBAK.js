'use strict';
var LIVERELOAD_PORT = 35729;
var lrSnippet = require('connect-livereload')({ port: LIVERELOAD_PORT });
var proxySnippet = require('grunt-connect-proxy/lib/utils').proxyRequest;

// configurable paths
var yo = {
  app: 'app',
  appFull: 'app',
  dist: 'public',
  distFull: 'public/shift-content-new',
  distTemp: 'public/modules/shift-content-new', //for revving
  proxyHost: '127.0.0.1', //only localhost or ip supported
  proxyPort: 8000, //but you can use port.
  webPath: '/modules/shift-content-new',
  routes: {
    //those are minified to correct location
    '/scripts/*path': '/app/scripts/[path]',
    '/css/*path': '/.tmp/css/[path]',
    '/components/*path': '/app/components/[path]',

    //those reflect actual path
    '/modules/shift-content-new/': '/app/index.html',
    '/modules/shift-content-new/img/*path': '/app/img/[path]',
    '/modules/shift-content-new/views/*path': '/app/views/[path]'
  }
};


module.exports = function (grunt) {
  // load all grunt tasks
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);

  try {
    yo.app = require('./bower.json').appPath || yo.app;
  } catch (e) {}

  grunt.initConfig({
    yeoman: yo,
    watch: {
      compass: {
        files: ['<%= yeoman.app %>/sass/{,*/}*.{scss,sass}'],
        tasks: ['compass:server']
      },
      livereload: {
        options: {
          livereload: LIVERELOAD_PORT
        },
        files: [
          '<%= yeoman.app %>/{,*/}*.html',
          '.tmp/css/{,*/}*.css',
          '<%= yeoman.app %>/sass/{,*/}*.css',
          '{.tmp,<%= yeoman.app %>}/scripts/{,*/}*.js',
          '<%= yeoman.app %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
        ]
      }
    },
    connect: {
      options: {
        port: 9002,
        hostname: 'localhost' // Change to '0.0.0.0' to access from outside.
      },
      proxies: [{
        context: '/',
        host: '<%= yeoman.proxyHost %>',
        port: '<%= yeoman.proxyPort %>',
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
      }
    },
    open: {
      server: {
        url: 'http://localhost:<%= connect.options.port %>/modules/shift-content-new/'
      }
    },
    clean: {
      dist: {
        files: [{
          dot: true,
          src: [
            '.tmp',
            '<%= yeoman.dist %>/*',
            '!<%= yeoman.dist %>/.git*'
          ]
        }]
      },
      server: '.tmp',
      finalize: [
        '<%= yeoman.dist %>/modules',
        '<%= yeoman.dist %>/views',
        '<%= yeoman.dist %>/*.*'
      ]
    },
    jshint: {
      options: {
        jshintrc: 'app/.jshintrc'
      },
      all: [
        'Gruntfile.js',
        '<%= yeoman.app %>/scripts/{,*/}*.js'
      ]
    },
    compass: {
      options: {
        sassDir: '<%= yeoman.app %>/sass',
        cssDir: '.tmp/css',
        generatedImagesDir: '.tmp/img/generated',
        imagesDir: '<%= yeoman.app %>/img',
        javascriptsDir: '<%= yeoman.app %>/scripts',
        fontsDir: '<%= yeoman.app %>/sass/fonts',
        importPath: '<%= yeoman.app %>/components',
        httpImagesPath: yo.webPath + '/img',
        httpGeneratedImagesPath: yo.webPath + '/img/generated',
        httpFontsPath: yo.webPath + '/css/fonts',
        relativeAssets: false
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
            '<%= yeoman.dist %>/scripts/{,*/}*.js',
            '<%= yeoman.distTemp %>/css/{,*/}*.css',
            '<%= yeoman.distTemp %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
            '<%= yeoman.distTemp %>/css/fonts/*'
          ]
        }
      }
    },
    useminPrepare: {
      html: ['<%= yeoman.app %>/*.html'],
      options: {
        dest: '<%= yeoman.dist %>'
      }
    },
    usemin: {
      html: ['<%= yeoman.dist %>/{,*/}*.html', '<%= yeoman.dist %>/{,*/}*.php'],
      css: ['<%= yeoman.distTemp %>/css/{,*/}*.css'],
      options: {
        dirs: ['<%= yeoman.dist %>']
      }
    },
    imagemin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yeoman.app %>/img',
          src: '{,*/}*.{png,jpg,jpeg}',
          dest: '<%= yeoman.distTemp %>/img'
        }]
      }
    },
    svgmin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yeoman.app %>/img',
          src: '{,*/}*.svg',
          dest: '<%= yeoman.distTemp %>/img'
        }]
      }
    },
    copy: {
      dist: {
        files: [
          {
            expand: true,
            dot: true,
            cwd: '<%= yeoman.app %>',
            dest: '<%= yeoman.distTemp %>',
            src: [
              '*.{ico,png,txt}',
              '.htaccess',
              'components/**/*',
              'img/{,*/}*.{gif,webp}'
            ]
          },
          {
            expand: true,
            cwd: '.tmp/img',
            dest: '<%= yeoman.distTemp %>/img',
            src: ['generated/*']
          },
          {
            expand: true,
            cwd: '<%= yeoman.app %>/sass/fonts',
            dest: '<%= yeoman.distTemp %>/css/fonts',
            src: ['*']
          },{
            expand: true,
            cwd: '<%= yeoman.app %>',
            dest: '<%= yeoman.dist %>',
            src: ['*.html', 'views/*.html', 'views/*.php']
          }
        ]
      },//dist
      back: {
        files: [
          {
            expand: true,
            cwd: '<%= yeoman.distTemp %>',
            dest: '<%= yeoman.distFull %>',
            src: [
              'css/*.css',
              'css/fonts/*.*',
              'img/{,*/}*.*',
              'scripts/{,*/}*.*',
              'components/**/*'
            ]
          }, {
            expand: true,
            cwd: '<%= yeoman.dist %>',
            dest: '<%= yeoman.distFull %>',
            src: [
              '*.html',
              '*.php',
              'views/{,*/}*.{html,php,phtml}'
            ]
          }
        ]
      }//back
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
    karma: {
      unit: {
        configFile: 'app/test/karma.conf.js',
        singleRun: true
      }
    },
    cdnify: {
      dist: {
        html: ['<%= yeoman.dist %>/*.html']
      }
    },
    ngmin: {
      dist: {
        files: [{
          expand: true,
          cwd: '<%= yeoman.distTemp %>/scripts',
          src: '*.js',
          dest: '<%= yeoman.distTemp %>/scripts'
        }]
      }
    },
    uglify: {
      dist: {
        files: {
          '<%= yeoman.distTemp %>/scripts/scripts.js': [
            '<%= yeoman.distTemp %>/scripts/scripts.js'
          ]
        }
      }
    }
  });

  grunt.registerTask('server', [
    'clean:server',
    'concurrent:server',
    'configureProxies',
    'connect:livereload',
    'open',
    'watch'
  ]);

  grunt.registerTask('test', [
    'karma'
  ]);

  grunt.registerTask('build', [
    'jshint',
    'test',
    'clean:dist',
    'useminPrepare', //parse index to find minification instruction for js/css
    'concurrent:dist', //compile compass to temp and minify images to dist
    'concat', //from usemin
    'copy:dist', //copy temp images, app scripts & templates to dist
    'cdnify', //replace local scripts with cdn
    'ngmin', //prepare scripts in dist
    'cssmin', //copy minified css from temp to dist
    'uglify', //minfy dist scripts per usemin config
    'rev', //rev assets
    'usemin:html', //update templates with revved assets
    'usemin:css', //update css with revved assets
    'copy:back', //copy everything back
    'clean:finalize'
  ]);

  grunt.registerTask('default', 'build');
};
