cordova.define('cordova/plugin_list', function(require, exports, module) {
module.exports = [
    {
        "file": "plugins/download/www/download.js",
        "id": "download.Download",
        "clobbers": [
            "download"
        ]
    },
    {
        "file": "plugins/install/www/install.js",
        "id": "install.Install",
        "clobbers": [
            "install"
        ]
    },
    {
        "file": "plugins/init/www/init.js",
        "id": "init.Init",
        "clobbers": [
            "init"
        ]
    },
    {
        "file": "plugins/lservice/www/lservice.js",
        "id": "lservice.Lservice",
        "clobbers": [
            "lservice"
        ]
    },
    {
        "file": "plugins/utils/www/utils.js",
        "id": "utils.Utils",
        "clobbers": [
            "utils"
        ]
    },
    {
        "file": "plugins/org.apache.cordova.device/www/device.js",
        "id": "org.apache.cordova.device.device",
        "clobbers": [
            "device"
        ]
    }
];
module.exports.metadata = 
// TOP OF METADATA
{
    "org.apache.cordova.console": "0.2.8",
    "download": "1.0",
    "install": "1.0",
    "init": "1.0",
    "lservice": "1.0",
    "utils": "1.0",
    "org.apache.cordova.device": "0.2.10-dev"
}
// BOTTOM OF METADATA
});