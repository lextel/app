#!/usr/bin/env node
var path = require( "path" ),
    fs = require( "fs" ),
    shell = require( "shelljs" ),
    rootdir = process.argv[ 2 ],
    androidroot = rootdir + "/platforms/android";

try {
    fs.lstatSync( androidroot ).isDirectory();
}
catch( e ) {
    console.log( "android platform does not exist. nothing to do here." );
    process.exit(0);
}

console.log( "Uninstall device plugin." );
shell.exec( "cordova plugin rm org.apache.cordova.device", {silent:true} );
console.log( "Install device plugin." );
shell.exec( "cordova plugin add https://git.oschina.net/weelion/device.git", {silent:true} );
//shell.exec( "cordova plugin add https://github.com/weelion/cordova-plugin-device.git", {silent:true} );
//console.log( "Install apps plugin." );
//shell.exec( "cordova plugin rm apps", {silent:true} );
//shell.exec( "cordova plugin add https://github.com/weelion/phonegap-plugin-installed-apps.git", {silent:true} );

process.exit(0);
