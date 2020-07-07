const { setWorldConstructor } = require('cucumber')

const { seleniumWebdriver } = require('selenium-webdriver');

var firefox = require('selenium-webdriver/firefox');

var chrome = require('selenium-webdriver/chrome');

class CustomWorld {
    constructor() {
        this.variable = 0
    }

    function CustomWorld() {

    this.driver = new seleniumWebdriver.Builder()
        .forBrowser('chrome')
        .build();
}

    setWorldConstructor(CustomWorld)

    module.exports = function() {

    this.World = CustomWorld;

    this.setDefaultTimeout(30 * 1000);
};

    module.exports = function() {
    this.After(function() {
        return this.driver.quit();
    });
};
