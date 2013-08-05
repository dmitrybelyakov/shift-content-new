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
    '/css/*path': '/.tmp/css/[path]',
    '/components/*path': '/app/components/[path]',
    '/modules/shift-content-new/': '/.tmp/index.html',
    '/modules/shift-content-new/img/*path': '/app/img/[path]',
    '/modules/shift-content-new/views/*path': '/app/views/[path]',
    '/modules/shift-content-new/scripts/*path': '/app/scripts/[path]'
  }
};


module.exports = function (grunt) {
  require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
  try {
    yo.app = require('./bower.json').appPath || yo.app;
  } catch (e) {}

  grunt.initConfig({
    yo: yo,
    watch: {
      compass: {
        files: ['<%= yo.app %>/sass/{,*/}*.{scss,sass}'],
        tasks: ['compass:server']
      },
      bake: {
        files: ['<%= yo.app %>/*.html', '<%= yo.app %>/view-partials/*.html'],
        tasks: ['bakeIndex']
      },
      livereload: {
        options: {
          livereload: LIVERELOAD_PORT
        },
        files: [
          '<%= yo.app %>/{,*/}*.html',
          '!<%= yo.app %>/{index,main}.html', //handled by baker
          '.tmp/{,*/}*.html',
          '.tmp/css/{,*/}*.css',
          '<%= yo.app %>/sass/{,*/}*.css',
          '{.tmp,<%= yo.app %>}/scripts/{,*/}*.js',
          '<%= yo.app %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}'
        ]
      }
    },
    bake: {
      index: {
        options: {},
        files: {'.tmp/index.html' : 'app/main.html'}
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
            '<%= yo.dist %>/*',
            '!<%= yo.dist %>/.git*'
          ]
        }]
      },
      server: '.tmp',
      finalize: [
        '<%= yo.dist %>/modules',
        '<%= yo.dist %>/views',
        '<%= yo.dist %>/*.*'
      ]
    },
    jshint: {
      options: {
        jshintrc: 'app/.jshintrc'
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
            '<%= yo.dist %>/scripts/{,*/}*.js',
            '<%= yo.distTemp %>/css/{,*/}*.css',
            '<%= yo.distTemp %>/img/{,*/}*.{png,jpg,jpeg,gif,webp,svg}',
            '<%= yo.distTemp %>/css/fonts/*'
          ]
        }
      }
    },
    useminPrepare: {
      html: ['<%= yo.dist %>/*.html'],
      options: {
        dest: '<%= yo.dist %>'
      }
    },
    usemin: {
      html: ['<%= yo.dist %>/{,*/}*.html', '<%= yo.dist %>/{,*/}*.php'],
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
            cwd: '.tmp',
            dest: '<%= yo.dist %>',
            src: ['*.html']
          },
          {
            expand: true,
            cwd: '<%= yo.app %>',
            dest: '<%= yo.dist %>',
            src: ['views/*.html', 'views/*.php']
          }
        ]
      },//dist
      back: {
        files: [
          {
            expand: true,
            cwd: '<%= yo.distTemp %>',
            dest: '<%= yo.distFull %>',
            src: [
              'css/*.css',
              'css/fonts/*.*',
              'img/{,*/}*.*',
              'scripts/{,*/}*.*',
              'components/**/*'
            ]
          }, {
            expand: true,
            cwd: '<%= yo.dist %>',
            dest: '<%= yo.distFull %>',
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
        html: ['<%= yo.dist %>/*.html']
      }
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

  grunt.registerTask('test', [
    'karma'
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
