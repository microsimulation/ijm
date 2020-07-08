require('chromedriver')
const {After,AfterAll, Before} = require('cucumber');
const {Builder, By, until} = require('selenium-webdriver');
const World = require('../world/world')
const chrome = require('selenium-webdriver/chrome');
var buildChromeDriver = function() {
    return new Builder().forBrowser("chrome").build();
};
const chromeDriver = buildChromeDriver()


Before(function () {
    // tslint:disable-next-line: no-invalid-this
    // this.state = new WorldState();
    this.state = new World(chromeDriver);
});

// After(function () {
//     // Assuming this.driver is a selenium webdriver
//     // this.state.driver.quit();
// });

AfterAll( function () {
  chromeDriver.quit();
});


