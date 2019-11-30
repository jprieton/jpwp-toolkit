/* jshint node:true */
module.exports = function ( grunt ) {

  'use strict';
  var sass = require( 'node-sass' );

  // Project configuration.
  grunt.initConfig( {

    // Setting folder templates.
    dirs: {
      css: 'assets/css',
      images: 'assets/images',
      js: 'assets/js',
      php: 'includes'
    },

    // JavaScript linting with JSHint.
    jshint: {
      options: {
        jshintrc: '.jshintrc'
      },
      all: [
        '<%= dirs.js %>/admin/*.js',
        '!<%= dirs.js %>/admin/*.min.js',
        '<%= dirs.js %>/frontend/*.js',
        '!<%= dirs.js %>/frontend/*.min.js'
      ]
    },

    // Sass linting with Stylelint.
    stylelint: {
      options: {
        configFile: '.stylelintrc'
      },
      all: [
        '<%= dirs.css %>/*.scss'
      ]
    },

    // Minify .js files.
    uglify: {
      options: {
        ie8: true,
        parse: {
          strict: false
        },
        output: {
          comments: /@license|@preserve|^!/
        }
      },
      admin: {
        files: [{
            expand: true,
            cwd: '<%= dirs.js %>/admin/',
            src: [
              '*.js',
              '!*.min.js'
            ],
            dest: '<%= dirs.js %>/admin/',
            ext: '.min.js'
          }]
      },
      frontend: {
        files: [{
            expand: true,
            cwd: '<%= dirs.js %>/frontend/',
            src: [
              '*.js',
              '!*.min.js'
            ],
            dest: '<%= dirs.js %>/frontend/',
            ext: '.min.js'
          }]
      }
    },

    // Compile all .scss files.
    sass: {
      compile: {
        options: {
          implementation: sass,
          sourceMap: 'none'
        },
        files: [{
            expand: true,
            cwd: '<%= dirs.css %>/',
            src: ['*.scss'],
            dest: '<%= dirs.css %>/',
            ext: '.css'
          }]
      }
    },

    // Generate RTL .css files.
    rtlcss: {
      woocommerce: {
        expand: true,
        cwd: '<%= dirs.css %>',
        src: [
          '*.css',
          '!*-rtl.css'
        ],
        dest: '<%= dirs.css %>/',
        ext: '-rtl.css'
      }
    },

    // Minify all .css files.
    cssmin: {
      minify: {
        expand: true,
        cwd: '<%= dirs.css %>/',
        src: ['*.css'],
        dest: '<%= dirs.css %>/',
        ext: '-min.css'
      }
    },

    // Concatenate common.css onto the admin.css files.
    concat: {
      admin: {
        files: {
          '<%= dirs.css %>/admin.css': ['<%= dirs.css %>/admin.css', '<%= dirs.css %>/common.css'],
          '<%= dirs.css %>/admin-rtl.css': ['<%= dirs.css %>/admin-rtl.css', '<%= dirs.css %>/common-rtl.css']
        }
      }
    },

    // Watch changes for assets.
    watch: {
      css: {
        files: ['<%= dirs.css %>/*.scss'],
        tasks: ['sass', 'rtlcss', 'postcss', 'cssmin']
      },
      js: {
        files: [
          '<%= dirs.js %>/admin/*js',
          '<%= dirs.js %>/frontend/*js',
          '!<%= dirs.js %>/admin/*.min.js',
          '!<%= dirs.js %>/frontend/*.min.js'
        ],
        tasks: ['jshint', 'uglify']
      }
    },

    // Generate POT files.
    makepot: {
      options: {
        type: 'wp-plugin',
        domainPath: 'languages',
        potHeaders: {
          'report-msgid-bugs-to': 'https://github.com/jprieton/jpwp-toolkit/issues',
          'language-team': 'LANGUAGE <EMAIL@ADDRESS>'
        }
      },
      dist: {
        options: {
          potFilename: 'jpwp-toolkit.pot',
          exclude: [
            'vendor/.*',
            'tests/.*',
            'tmp/.*'
          ]
        }
      }
    },

    // Check textdomain errors.
    checktextdomain: {
      options: {
        text_domain: 'jpwp-toolkit',
        keywords: [
          '__:1,2d',
          '_e:1,2d',
          '_x:1,2c,3d',
          'esc_html__:1,2d',
          'esc_html_e:1,2d',
          'esc_html_x:1,2c,3d',
          'esc_attr__:1,2d',
          'esc_attr_e:1,2d',
          'esc_attr_x:1,2c,3d',
          '_ex:1,2c,3d',
          '_n:1,2,4d',
          '_nx:1,2,4c,5d',
          '_n_noop:1,2,3d',
          '_nx_noop:1,2,3c,4d'
        ]
      },
      files: {
        src: [
          '**/*.php', // Include all files
          '!includes/libraries/**', // Exclude libraries/
          '!node_modules/**', // Exclude node_modules/
          '!tests/**', // Exclude tests/
          '!vendor/**', // Exclude vendor/
          '!tmp/**', // Exclude tmp/
          '!packages/*/vendor/**'   // Exclude packages/*/vendor
        ],
        expand: true
      }
    },

    // PHP Code Sniffer.
    phpcs: {
      options: {
        bin: 'vendor/bin/phpcs'
      },
      dist: {
        src: [
          '**/*.php', // Include all php files.
          '!includes/api/legacy/**',
          '!includes/libraries/**',
          '!node_modules/**',
          '!tests/cli/**',
          '!tmp/**',
          '!vendor/**'
        ]
      }
    },

    // Autoprefixer.
    postcss: {
      options: {
        processors: [
          require( 'autoprefixer' )
        ]
      },
      dist: {
        src: [
          '<%= dirs.css %>/*.css'
        ]
      }
    }
  } );

  // Load NPM tasks to be used here.
  grunt.loadNpmTasks( 'grunt-sass' );
  grunt.loadNpmTasks( 'grunt-rtlcss' );
  grunt.loadNpmTasks( 'grunt-postcss' );
  grunt.loadNpmTasks( 'grunt-stylelint' );
  grunt.loadNpmTasks( 'grunt-wp-i18n' );
  grunt.loadNpmTasks( 'grunt-checktextdomain' );
  grunt.loadNpmTasks( 'grunt-contrib-jshint' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
  grunt.loadNpmTasks( 'grunt-contrib-concat' );
  grunt.loadNpmTasks( 'grunt-contrib-copy' );
  grunt.loadNpmTasks( 'grunt-contrib-uglify' );
  grunt.loadNpmTasks( 'grunt-contrib-watch' );

  // Register tasks.
  grunt.registerTask( 'default', [
    'js',
    'css',
    'i18n'
  ] );

  grunt.registerTask( 'js', [
    'jshint',
    'uglify:admin',
    'uglify:frontend'
  ] );

  grunt.registerTask( 'css', [
    'sass',
    'rtlcss',
    'postcss',
    'cssmin',
    'concat'
  ] );

  grunt.registerTask( 'assets', [
    'js',
    'css'
  ] );

  grunt.registerTask( 'e2e-build', [
    'uglify:admin',
    'uglify:frontend',
    'css'
  ] );

  grunt.registerTask( 'contributors', [
    'prompt:contributors',
    'shell:contributors'
  ] );

  // Only an alias to 'default' task.
  grunt.registerTask( 'dev', [
    'default'
  ] );

  grunt.registerTask( 'i18n', [
    'checktextdomain',
    'makepot'
  ] );

  grunt.registerTask( 'e2e-tests', [
    'shell:e2e_tests'
  ] );

  grunt.registerTask( 'e2e-tests-grep', [
    'shell:e2e_tests_grep'
  ] );

  grunt.registerTask( 'e2e-test', [
    'shell:e2e_test'
  ] );
};
