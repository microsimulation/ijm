import {When, Then, Given} from 'cucumber';
import {expect} from 'chai';
import {By} from 'selenium-webdriver';
import config from '../config';
import xpaths from '../config/xpaths';
import pages from '../config/pages';
import * as https from 'https';
import * as fs from 'fs';
import path from 'path';

Given(/^user navigates to "([^"]*)" page$/, {timeout: 50 * 1000}, async function (pageName) {
    try {
        if (pageName === "Home") {
            await this.state.driver.get(config.url)
        } else {
            await this.state.driver.get(`${config.url}/${pageName}`);
        }
        const buffer = await this.state.driver.takeScreenshot();

        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

//When section

When(/^user is on the Home page$/, async function () {
    const title = await this.state.driver.getTitle()
    expect(title).to.equal("Latest research | International Journal of Microsimulation");
    return title;
});

When(/^user clicks on the second article from the list$/, {timeout: 30 * 1000}, async function () {
    try {
        const result = await this.state.driver.findElement(By.xpath(xpaths["Second article"]))
        result.click();
        const buffer = await this.state.driver.takeScreenshot();
        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

When(/^user clicks on "([^"]*)"$/, async function (element) {
    const result = await this.state.driver.findElement(By.xpath(xpaths[element]));
    await result.click();
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

When(/^user navigates to "([^"]*)"$/, async function (articleNumber) {
    await this.state.driver.get(`${config.url}articles/${articleNumber}`);
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

When(/^user clicks on 'Linked volume' of the random article$/, async function () {
    try {
        const result = await this.state.driver.findElement(By.xpath(xpaths["Random issue link"]));
        result.click();
        const buffer = await this.state.driver.takeScreenshot();
        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

When(/^user selects "([^"]*)"$/, async function (extraRef) {
    const elemMap = xpaths.downloadButtons
    expect(elemMap[extraRef]).to.be.a('string');
    const result = await this.state.driver.findElement(By.xpath(elemMap[extraRef]));
    result.click();
});

When(/^user clicks on "([^"]*)" subject$/, async function (subject) {
    try {
        const result = await this.state.driver.findElement(By.linkText(subject));
        result.click();
        const buffer = await this.state.driver.takeScreenshot();
        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

When(/^user searches for "([^"]*)"$/, async function (keys) {
    const result = await this.state.driver.findElement(By.xpath(xpaths["Search input"]))
    result.sendKeys(keys);
    const submit = await this.state.driver.findElement(By.xpath(xpaths["Search submit"]))
    submit.click();
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

//Then section
Then(/^a list of 10 articles is displayed$/, {timeout: 15 * 1000}, async function () {
    const result = await this.state.driver.findElements(By.xpath(xpaths["List of articles"]));
    expect(result.length).to.equal(10);
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

Then(/^"([^"]*)" is displayed$/, {timeout: 30 * 1000}, async function (pageName) {
    const currentUrl = await this.state.driver.getCurrentUrl()
    expect(currentUrl).to.contains(pages[pageName]);
});

//compare header of the page
Then(/^"([^"]*)" page is displayed$/, async function (articleType) {
    const result = await this.state.driver.findElement(By.xpath(xpaths["Page header"])).getText()
    expect(result).to.equal(articleType);
});

Then(/^the article type is "([^"]*)"$/, {timeout: 15 * 1000}, async function (articleType) {
    const result = await this.state.driver.findElement(By.xpath(xpaths["Article type"])).getText()
    expect(result).to.equal(articleType);
});

Then(/^article preview doesn't contain date$/, async function () {
    const result = await this.state.driver.findElement(By.xpath(xpaths["Article preview footer"])).getId()
    expect(result).not.equal("\\w{3}\\s\\d{2},\\s\\d{4}");
});

Then(/^section "([^"]*)" is displayed$/, async function (sectionName) {
    const result = await this.state.driver.findElement(By.xpath(xpaths["Subjects"])).getText();
    expect(result).to.equal(sectionName);
});

Then(/^the following special type of articles is displayed:$/, async function (articleTypes) {
    const types = articleTypes.rawTable.flat()
    const elements = await this.state.driver.findElement(By.xpath(xpaths["Special article types"])).getText()
    expect(result).to.not.equal(null);
    const parsed = elements.split("\n")
    expect(parsed).to.eql(types)
});

Then(/^a "([^"]*)" file is downloaded$/,async function (type) {
    const elemMap = xpaths.downloadButtons
    const result = await this.state.driver.findElement(By.xpath(elemMap[type])).getAttribute("href");
    const [filename] = path.basename(result).split("?",1);
    const file = fs.createWriteStream(path.join(config.downloadDir, filename));
    https.get(result, function(response) {
        response.pipe(file);
    });
});
