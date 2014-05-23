var exec = require("cordova/exec");

var Lservice = function () {
    this.name = "Lservice";
};

Lservice.prototype.start = function () {
	exec(null, null, "Lservice", "start", []);
};

module.exports = new Lservice();
