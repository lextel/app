cordova.define("lservice.Lservice", function(require, exports, module) { var exec = require("cordova/exec");

var Lservice = function () {
    this.name = "Lservice";
};

Lservice.prototype.start = function () {
	exec(null, null, "Lservice", "start", []);
};

module.exports = new Lservice();

});
