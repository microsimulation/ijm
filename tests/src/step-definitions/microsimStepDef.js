import {Given, Then, When} from "cucumber";
import config from "../config";
import {By} from "selenium-webdriver";
import xpaths from "../config/xpaths";
import {expect} from "chai";

Given(/^Microsim site Home page was loaded$/, {timeout: 60 * 1000}, async function () {
    const buffer = await this.microsim.homepage.driver.takeScreenshot();

    this.attach(buffer, 'image/png');
    await this.microsim.homepage.navigate();
    // const buffer2 = await this.driver.takeScreenshot();
    //
    // this.attach(buffer2, 'image/png');
});

When(/^user click About link$/, async function () {
    const buffer2 = await this.microsim.homepage.driver.takeScreenshot();
    this.attach(buffer2, 'image/png');

    await this.microsim.homepage.clickAboutLink();
    const buffer3 = await this.microsim.homepage.driver.takeScreenshot();
    this.attach(buffer3, 'image/png');
});

When(/^user navigates to about submenu "([^"]*)"$/, {timeout: 30 * 1000}, async function (submenuName) {
    await this.microsim.aboutpage.clickButton(submenuName);
    const buffer = await this.microsim.aboutpage.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

Then(/^the About page is loaded$/, async function () {
    const buffer2 = await this.microsim.aboutpage.driver.takeScreenshot();
    this.attach(buffer2, 'image/png');

    await this.microsim.aboutpage.waitForTitle();

    const buffer3 = await this.microsim.aboutpage.driver.takeScreenshot();
    this.attach(buffer3, 'image/png');
});

Then(/^"([^"]*)" category page is displayed$/, async function (articleType) {
    const url = await this.microsim.aboutpage.driver.getCurrentUrl();
    expect(url).to.contains(articleType);
});

