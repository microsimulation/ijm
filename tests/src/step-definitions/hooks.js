import 'chromedriver';
import { AfterAll, Before } from 'cucumber';
import { Builder } from 'selenium-webdriver';
import World from '../world/world';
import chrome from 'selenium-webdriver/chrome';
import config from '../config';

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
            .setChromeOptions(new chrome.Options().addArguments("--no-recovery-component"))
            .build();
    }
};

const chromeDriver = buildChromeDriver()

Before(function () {
    this.state = new World(chromeDriver);
});

AfterAll(function () {
    chromeDriver.quit();
});
