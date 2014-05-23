cordova.define("install.Install", function(require, exports, module) { var exec = require("cordova/exec");

var Install = function () {
    this.name = "Install";
};

Install.prototype.install = function (path, pg, point, func) {
	exec(func, null, "Install", "install", [path, pg, point]);
};

Install.prototype.execute = function (pg) {
	exec(null, null, "Install", "execute", [pg]);
};

module.exports = new Install();


});
