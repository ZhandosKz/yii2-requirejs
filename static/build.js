"use strict";

module.exports = function() {
    var md5 = require("md5");
    var buildPath = 'build/' + md5(Date.now());

    return {
        build: {
            options: {
                appDir: "static",
                mainConfigFile: "static/common.js",
                baseUrl: "./",
                dir: "web/" + buildPath,
                // Css urls normalization from web/build/tag to web
                siteRoot: '../../',
                wrapShim: true,
                modules: [
                    {
                        name: "common",
                        include: [
                            "yii"
                        ]
                    },
                    {
                        name: "entries/home",
                        include: [
                            "home/app"
                        ]
                    }
                ]
            }
        },
        requireJsConfigUpdate: {
            src: "config/requirejs.php",
            overwrite: true,
            replacements: [{
                from: /\'resourcesUrl\' => \'(.*)\'/,
                to: "'resourcesUrl' => '/" + buildPath + "'"
            }]
        }
    };
};