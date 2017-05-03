'use strict';
module.exports = function(grunt) {
    var path = require('path');
    require('load-grunt-tasks')(grunt);
    require('time-grunt')(grunt);

    var sassFileList = [
        'webroot/assets/scss/*.scss',
        'webroot/assets/scss/base/*.scss',
        'webroot/assets/scss/components/*.scss',
        'webroot/assets/scss/layout/*.scss',
        'webroot/assets/scss/settings/*.scss',
        'webroot/assets/scss/tools/*.scss'
    ];

    /*var cssFileList = [
        'webroot/assets/scss/main.css'
    ];*/

    grunt.loadNpmTasks('grunt-criticalcss');
    grunt.loadNpmTasks('grunt-assets-inline');

    grunt.registerTask('pagespeed', ['criticalcss', 'cssmin', 'assets_inline']);

    grunt.initConfig({

        sass: {
            assets: {
                options: {
                    style: 'expanded',
                    sourcemap: 'none'
                },
                files: {
                    'webroot/assets/scss/main.css': sassFileList
                }
            }
        },

        criticalcss: {
             custom: {
                 options: {
                     url: 'http://modxskeleton.nl.sytske/',
                     width: 1200,
                     height: 900,
                     outputfile: 'webroot/assets/scss/above-the-fold.css',
                     filename: 'webroot/assets/scss/main.css', // Using path.resolve( path.join( ... ) ) is a good idea here
                     buffer: 800*1024,
                     ignoreConsole: false
                 }
             }
        },

        assets_inline: {
            all: {
                options: {
                    includeTag: '?assets-inline',
                    cssDir: path.resolve() + '/webroot/assets/scss/',
                    verbose: true,
                    cssTags: {
                        start: '<style id="above-the-fold-css">',
                        end: '</style>'
                    }
                },
                files: {
                    'private/core/components/site/elements/chunks/html/head.chunk.tpl': 'private/core/components/site/elements/chunks/html/head.build.chunk.tpl',
                }
            }
        },

        cssmin: {
            options: {
                sourceMap: false
            },
            compress: {
                files: {
                    'webroot/assets/scss/above-the-fold.min.css': [
                        'webroot/assets/scss/above-the-fold.css'
                    ],
                }
            }
        },

        watch: {
            sass: {
                files: sassFileList,
                tasks: ['sass']
            },
        }

    });
};
