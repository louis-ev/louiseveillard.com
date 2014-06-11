// For all available options, see node_modules/pho-dev-stack/config.js
// These are production build settings, see gulpfile.js for development settings
var gulp = require('gulp');

var extend = require('node.extend');
var substituteConfig = require('./substitute-config');

require('pho-devstack')(gulp, {
  browserify: {
    debug: false,
    transforms: {
      "browserify-ngmin": false,
      uglifyify: true
    }
  },
  less: {
    sourceMap: false
  },
  livereload: {
    enabled: false
  },
  plumber: {
    enabled: false
  },
  rename: {
    enabled: true
  },
  watch: {
    enabled: false
  },
  spritesPreprocessor: {
    enabled: true,
    prefix: '../images/sprites/' // change this to prefix path before sprites. '/images/sprites/' for absolute paths
  },
  base64: {
    enabled: true,
    // baseDir: 'src' // uncomment if you are using absolute paths
  },
  substituter: extend(true, substituteConfig, {
    // cdn: 'http://example.com' // url to your cdn server
  }),

  copy: ['humans.txt', 'favicon.ico', 'bower_components/jquery/jquery.min.js', 'bower_components/modernizr/modernizr.js', 'scripts/balls.pde', 'scripts/plugins.js', 'scripts/layout.js']
});

// If needed, redefine tasks here
