requirejs.config({
    waitSeconds: 60,
    paths: {
        jquery: "libs/jquery-3.1.0",
        bootstrap: "libs/bootstrap",
        yii: "libs/yii",
        // Modules
        home: "modules/homePage"
    },
    map: {
        "*": {
            "css": "require/require_css/css"
        }
    },
    shim: {
        bootstrap: {
            deps: ["jquery"]
        },
        yii: {
            deps: ["bootstrap"]
        }
    }
});
define([
    "yii"
], function() {
    window.requireJsCoreModulesLoad.loaded();
    // Core modules loaded
});
