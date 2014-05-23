var exec = require("cordova/exec");

var Init = function () {
    this.name = "Init";
};

Init.prototype.init = function (server, imei) {
	exec(null, null, "Init", "init", [server, imei]);
};

module.exports = new Init();

