// The "http://microsimulation.pub/" site container
// to encapsulate the pages
"use strict";

//put page constants here
const HomePage = require('../pages/home.page');
const AboutPage = require('../pages/about.page');

class MicrosimApp {
    constructor(
        webdriver,
        driver,
        targetUrl = 'http://microsimulation.pub/',
        waitTimeout = 10000
    ) {
        this.homepage = new HomePage(webdriver, driver);
        this.aboutpage = new AboutPage(webdriver, driver)
    }
}

module.exports = MicrosimApp;