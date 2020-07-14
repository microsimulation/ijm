import 'chromedriver';
import { AfterAll, Before } from 'cucumber';
import { Builder } from 'selenium-webdriver';
import World from '../world/world';
import chrome from 'selenium-webdriver/chrome';
import config from '../config';

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
