import 'chromedriver';
import {AfterAll, Before} from 'cucumber';
import {Builder} from 'selenium-webdriver';
import fs from 'fs';
import path from 'path';
import World from '../world/world';
import chrome from 'selenium-webdriver/chrome';
import config from '../config';

const MicrosimApp = require('../pom/site/microsim.app');
const webddriver = require('selenium-webdriver');

const buildChromeDriver = function () {
    const { mode, windowSize } = config.headless;

    if (mode) {
        return new Builder()
            .forBrowser("chrome")
            .setChromeOptions(
                new chrome.Options()
                    .windowSize(windowSize)
                    .headless()
            )
            .build();
    } else {
        return new Builder()
            .forBrowser("chrome")
            .setChromeOptions(
                new chrome.Options()
                    .addArguments("--no-recovery-component"))
            .build();
    }
};

const chromeDriver = buildChromeDriver()

Before(function () {
    this.state = new World(chromeDriver);
    this.microsim = new MicrosimApp(webddriver, chromeDriver);
});

AfterAll(function () {
    chromeDriver.quit();

    const {downloadDir} = config;
    const files = fs.readdirSync(downloadDir);
    files.forEach(file => file !== '.gitkeep' && fs.unlinkSync(path.join(downloadDir, file)));
});