phonegap-plugin-download
=======================

This PhoneGap plugin allows you to download files to SDcard.

 > only support android platfrom


#### Installation

Automatically (CLI / Plugman)

download is compatible with Cordova Plugman, compatible with PhoneGap 3.0 CLI, here's how it works with the CLI:

    $ phonegap local plugin add https://github.com/weelion/phonegap-plugin-download.git
or

    $ cordova plugin add https://github.com/weelion/phonegap-plugin-download.git


run this command afterwards (backup your project first!):

    $ cordova prepare

dirsize.js is brought in automatically. There is no need to change or add anything in your html.


#### Usage

js

    // start download
    window.download.start(url, function() {
         console.log('download start');
    });

    // update progress (it will be call when downloading)
    function downloadProgress(progress) {
        console.log('download' + progress + '%');
    }

    // stop download
    window.download.stop(url, function() {
         console.log('download stop');
    });

#### Have a nice day!
