require('chromedriver')
const {AfterAll, Before} = require('cucumber');
const {Builder} = require('selenium-webdriver');
const World = require('../world/world')
const chrome = require('selenium-webdriver/chrome');
const config = require('../../config.json');

const buildChromeDriver = function () {
    const headless_run = config.headless;
    if(headless_run){
        return new Builder().forBrowser("chrome").setChromeOptions(new chrome.Options().headless()).build();
    }
    else{
        return new Builder().forBrowser("chrome").build();
    }
};

const chromeDriver = buildChromeDriver()

Before(function () {
    this.state = new World(chromeDriver);
});

AfterAll(function () {
    chromeDriver.quit();
});
