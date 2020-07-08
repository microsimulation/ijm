'use strict';

var {defineSupportCode} = require('cucumber');
var {Builder, By, until} = require('selenium-webdriver');
var fs = require('fs');

var buildChromeDriver = function() {
    return new Builder().forBrowser("chrome").build();
};

var buildFirefoxDriver = function() {
    return new Builder().forBrowser("firefox").build();
};

defineSupportCode(function({setDefaultTimeout}) {
    setDefaultTimeout(60 * 1000);
});

var World = function World() {

    var screenshotPath = "screenshots";

    this.driver = buildChromeDriver();

    if(!fs.existsSync(screenshotPath)) {
        fs.mkdirSync(screenshotPath);
    }

};

defineSupportCode(function({setWorldConstructor}) {
    setWorldConstructor(World);
});
