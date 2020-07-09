require('chromedriver')
const {After, Before} = require('cucumber');
const {Builder} = require('selenium-webdriver');
const World = require('../world/world')
const chrome = require('selenium-webdriver/chrome');

const buildChromeDriver = function () {
    return new Builder().forBrowser("chrome").build();
};

const chromeDriver = buildChromeDriver()

Before(function () {
    this.state = new World(chromeDriver);
});

After(function () {
    chromeDriver.quit();
});
