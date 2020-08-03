import {Given, Then, When} from 'cucumber';
import {expect} from 'chai';
import {By} from 'selenium-webdriver';
import * as http from 'http';
import * as fs from 'fs';
import path from 'path';
import axios from 'axios';

import config from '../config';
import xpaths from '../config/xpaths';

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

When(/^user clicks on "([^"]*)" from the list$/, {timeout: 30 * 1000}, async function (article) {
    try {
        const articleElement = await this.state.driver.findElement(By.xpath(xpaths[article]))
        const articleName = (await articleElement.getText());
        console.log("articleName: " + articleName);
        this.data.currentArticle.name = articleName;

        await articleElement.click();
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

When(/^user navigates to "([^"]*)"$/, {timeout: 30 * 1000}, async function (articleNumber) {
    await this.state.driver.get(`${config.url}articles/${articleNumber}`);
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

When(/^user navigates to subject "([^"]*)"$/, async function (subjectNumber) {
    await this.state.driver.get(`${config.url}subjects/${subjectNumber}`);
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

When(/^user clicks on 'Linked volume' of the random article$/, async function () {
    try {
        const volumeElement = await this.state.driver.findElement(By.xpath(xpaths["Random issue link"]));
        const volumeName = (await volumeElement.getText());
        console.log("volumeName: " + volumeName);
        this.data.currentVolume.name = volumeName;
        console.log("datastore: " + JSON.stringify(this.data, null, '\t'));
        await volumeElement.click();
        const buffer = await this.state.driver.takeScreenshot();
        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
});

When(/^user selects "([^"]*)"$/, {timeout: 15 * 1000}, async function (extraRef) {
    const elemMap = xpaths.downloadButtons
    expect(elemMap[extraRef]).to.be.a('string');
    const result = await this.state.driver.findElement(By.xpath(elemMap[extraRef]));
    await result.click();
});

When(/^user clicks on "([^"]*)" subject$/, async function (subject) {
    try {
        const result = await this.state.driver.findElement(By.linkText(subject));
        await result.click();
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
    await submit.click();
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
    const pageTitle = await this.state.driver.getTitle();
    console.log("current pageTitle: " + pageTitle);
    console.log("datastore: " + JSON.stringify(this.data));
    expect(pageTitle.toString().toUpperCase()).to.contains(this.data.currentVolume.name);
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
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

Then(/^a "([^"]*)" file is downloaded$/, async function (type) {
    const elemMap = xpaths.downloadButtons
    const downloadBtnUrl = await this.state.driver.findElement(By.xpath(elemMap[type])).getAttribute("href");
    const [filename] = path.basename(downloadBtnUrl).split("?", 1);
    await new Promise((resolve, reject) =>
        http.get(downloadBtnUrl, (res) => {
            expect(res.statusCode).to.equal(200);
            const file = fs.createWriteStream(path.join(config.downloadDir, filename));
            res.pipe(file);
            res.on('end', () => resolve());
        })
            .on('error', (err) => reject(err))
    );
});

Given(/^Microsim site Home page was loaded$/, async function () {
    const buffer = await this.state.driver.takeScreenshot();

    this.attach(buffer, 'image/png');
    await this.microsim.homepage.navigate();
    const buffer2 = await this.state.driver.takeScreenshot();

    this.attach(buffer2, 'image/png');
});

When(/^user click About link$/, async function () {
    const buffer2 = await this.state.driver.takeScreenshot();

    this.attach(buffer2, 'image/png');

    await this.microsim.homepage.clickAboutLink();
    const buffer3 = await this.state.driver.takeScreenshot();

    this.attach(buffer3, 'image/png');
});

Then(/^the About page is loaded$/, async function () {
    const buffer2 = await this.state.driver.takeScreenshot();

    this.attach(buffer2, 'image/png');

    await this.microsim.homepage.waitForTitle();
    const buffer3 = await this.state.driver.takeScreenshot();

    this.attach(buffer3, 'image/png');
});
Then(/^following sections are displayed:$/, async function (articleSections) {
    for (const section of articleSections.rawTable.flat()) {
        console.log("Section: " + section);
        console.log("xpaths: " + xpaths.articleSections[section]);
        const paragraphNameWebElement = await this.state.driver.findElement(By.xpath(xpaths.articleSections[section]));
        const paragraphNameArray = (await paragraphNameWebElement.getText()).split(".");
        const resultValue = paragraphNameArray[paragraphNameArray.length - 1].toString().trim();
        expect(resultValue).to.equal(section);
        await resultValue.isDisplayed;
    }
});
When(/^user selects "([^"]*)" checkbox$/, async function (element) {
    const result = await this.state.driver.findElement(By.xpath(xpaths.researchCategories[element]));
    await result.click();
    const buffer = await this.state.driver.takeScreenshot();
    this.attach(buffer, 'image/png');
});

Then(/^Images in article are loaded$/, {timeout: 20 * 1000}, async function () {
    const allImages = await this.state.driver.findElements(By.xpath(xpaths["Images"]));
    for (const image of allImages) {
        const imageURL = await image.getAttribute("src");
        const response = await axios.get(imageURL);
        expect(response.status).to.equal(200);
    }
});
When(/^user logs in to Mendeley$/, {timeout: 100 * 1000}, async function () {
    const username = await this.state.driver.findElement(By.xpath(xpaths.mendeley["Email"]));
    const continueButton = await this.state.driver.findElement(By.xpath(xpaths.mendeley["Continue"]));
    await username.sendKeys(process.env.mendeley_username);
    await continueButton.click();
    const password = await this.state.driver.findElement(By.xpath(xpaths.mendeley["Password"]));
    const submit = await this.state.driver.findElement(By.xpath(xpaths.mendeley["Continue"]));
    await password.sendKeys(process.env.mendeley_password);
    await submit.click();
});
When(/^user clicks on "([^"]*)" (button|menu item)$/, {timeout: 50 * 1000}, async function (elementName, type) {
    const result = await this.state.driver.findElement(By.xpath(xpaths.mendeley[elementName]));
    await result.click();
});
Then(/^article is displayed in Mendeley$/,{timeout: 50 * 1000}, async function () {
    const expectedArticleName = this.data.currentArticle.name;
    const cancel1 = await this.state.driver.findElement(By.xpath(xpaths.mendeley["x"]));
    await cancel1.click();
    const cancel2 = await this.state.driver.findElement(By.xpath(xpaths.mendeley["x1"]));
    await cancel2.click();
    const actualArticleName = await this.state.driver.findElement(By.xpath(xpaths.mendeley["FirstElement"])).getText();
    await expect(expectedArticleName).to.equal(actualArticleName);
});