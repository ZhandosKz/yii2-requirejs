"use strict";

module.exports = function(grunt) {
    // Получаем конфигурацию для задач
    var requireJs = require("./static/build")();

    grunt.initConfig({
        requirejs: {
            build: requireJs.build
        },
        replace: {
            requireJsConfig: requireJs.requireJsConfigUpdate
        }
    });

    grunt.loadNpmTasks('grunt-text-replace');
    grunt.loadNpmTasks('grunt-contrib-requirejs');

    grunt.registerTask("default", ["requirejs:build", "replace:requireJsConfig"])
};