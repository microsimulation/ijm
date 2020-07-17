import {When, Then, Given} from 'cucumber';
import {expect} from 'chai';
import {By} from 'selenium-webdriver';
import config from '../config';
import xpaths from '../config/xpaths';
import pages from '../config/pages';

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

When(/^user clicks on the first article from the list$/, {timeout: 30 * 1000}, async function () {
    try {
        const result = await this.state.driver.findElement(By.xpath("//*[@id='listing']/li[1]/div/header/h4/a"))
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

When(/^user clicks on 'Linked volume' of the first article$/, async function () {
    try {
        const result = await this.state.driver.findElement(By.xpath("//*[@id='listing']/li[1]/div/div/div/a"));
        result.click();
        const buffer = await this.state.driver.takeScreenshot();
        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

When(/^user selects "([^"]*)"$/, async function (extraRef) {
    const elemMap = xpaths.articleDownloadLinks
    expect(elemMap[extraRef]).to.be.a('string');
    const result = await this.state.driver.findElement(By.xpath(elemMap[extraRef]))
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
    const result = await this.state.driver.findElements(By.xpath('//*[@id="listing"]/li'));
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
    const result = await this.state.driver.findElement(By.xpath('//*[@id="maincontent"]//h1')).getText()
    expect(result).to.equal(articleType);
});

Then(/^the article type is "([^"]*)"$/, {timeout: 15 * 1000}, async function (articleType) {
    const result = await this.state.driver.findElement(By.xpath("//*[@id='maincontent']/header/div[4]/div/a")).getText()
    expect(result).to.equal(articleType);
});

Then(/^article preview doesn't contain date$/, async function () {
    const result = await this.state.driver.findElement(By.xpath('//*[@id="listing"]/li[2]/div/footer/div[1]/a')).getId()
    expect(result).not.equal("\\w{3}\\s\\d{2},\\s\\d{4}");
});

Then(/^section "([^"]*)" is displayed$/, async function (sectionName) {
    const result = await this.state.driver.findElement(By.xpath('//*[@id="subjects"]//p')).getText();
    expect(result).to.equal(sectionName);
});

Then(/^the following special type of articles is displayed:$/, async function (articleTypes) {
    const types = articleTypes.rawTable.flat()
    const elements = await this.state.driver.findElement(By.xpath('//*[@id="section-listing--types"]')).getText()
    expect(result).to.not.equal(null);
    const parsed = elements.split("\n")
    expect(parsed).to.eql(types)
});
