const {When, Then, Given} = require('cucumber');
const {expect} = require('chai')
const {By} = require('selenium-webdriver');
const config = require('../../config.json')

Given(/^user navigates to 'Home' page$/, {timeout: 50 * 1000}, function (callback) {
    this.state.driver.get(config.url).then((result) => {
        callback(null, result)
    }).catch(err => {
        callback(err)
    })

});

When(/^user is on the Home page$/, function () {
    this.state.driver.getTitle().then(function (title) {
        expect(title).to.equal("Latest research | International Journal of Microsimulation");
        return title;
    });
});

Then(/^a list of 10 articles is displayed$/, {timeout: 15 * 1000}, function (callback) {
    this.state.driver.findElements(By.xpath('//*[@id="listing"]/li')).then((result) => {
        expect(result.length).to.equal(10);
        callback(null, result)
    }).catch(err => {
        callback(err)
    });

    setTimeout(() => {
        callback()
    }, 10000)
});

When(/^user clicks on the first article from the list$/, {timeout: 15 * 1000}, function (callback) {
    this.state.driver.findElement(By.xpath("//*[@id='listing']/li[1]/div/header/h4/a"))
        .then((result) => {
            result.click();
            callback(null, result)
        });

});

Then(/^'Article' page is displayed$/, function () {
    this.state.driver.getCurrentUrl().then(function (title) {
        expect(title).to.contains("articles");
    });
});

When(/^user clicks on 'Linked volume'$/, {timeout: 25 * 1000}, function (callback) {
    this.state.driver.findElement(By.xpath("//*[@id='maincontent']/header/div[5]/div/a"))
        .then((result) => {
            result.click();
            callback(null, result)
        });
});

Then(/^'Issues' page is displayed$/, {timeout: 15 * 1000}, function (callback) {
    this.state.driver.getCurrentUrl().then(function (title) {
        expect(title).to.contains("collections");
        setTimeout(() => {
            callback()
        }, 10000)
    });
});
