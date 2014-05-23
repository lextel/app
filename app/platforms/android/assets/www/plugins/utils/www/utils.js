cordova.define("utils.Utils", function(require, exports, module) { var exec = require("cordova/exec");

var Utils = function () {
    this.name = "Utils";
};

Utils.prototype.installed = function (func) {
	exec(func, null, "Utils", "installed", []);
};

Utils.prototype.getToken = function (func) {
	exec(func, null, "Utils", "getToken", []);
};

Utils.prototype.setToken = function (token) {
	exec(null, null, "Utils", "setToken", [token]);
};

Utils.prototype.getPoint = function (func) {
	exec(func, null, "Utils", "getPoint", []);
};

Utils.prototype.savePoint = function (point) {
	exec(null, null, "Utils", "savePoint", [point]);
};

module.exports = new Utils();

});
