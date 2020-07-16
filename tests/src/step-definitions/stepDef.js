import {When, Then, Given} from 'cucumber';
import {expect} from 'chai';
import {By} from 'selenium-webdriver';
import config from '../config';

Given(/^user navigates to 'Home' page$/, {timeout: 50 * 1000}, async function () {
    try {
        const result = await this.state.driver.get(config.url)
        const buffer = await this.state.driver.takeScreenshot();

        this.attach(buffer, 'image/png');
    } catch (e) {
        console.log(e);
    }
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
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

When(/^user clicks on the first article from the list$/, {timeout: 15 * 1000}, function (callback) {
    this.state.driver.findElement(By.xpath("//*[@id='listing']/li[1]/div/header/h4/a"))
        .then((result) => {
            result.click();
            callback(null, result)
        });
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

Then(/^'Article' page is displayed$/, {timeout: 30 * 1000}, function (callback) {
    this.state.driver.getCurrentUrl().then(function (title) {
        expect(title).to.contains("articles");
    });
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

When(/^user clicks on 'Linked volume'$/, {timeout: 25 * 1000}, function (callback) {
    this.state.driver.findElement(By.xpath("//*[@id='maincontent']/header/div[5]/div/a"))
        .then((result) => {
            result.click();
            callback(null, result)
        });
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

Then(/^'Issues' page is displayed$/, {timeout: 15 * 1000}, function (callback) {
    this.state.driver.getCurrentUrl().then(function (title) {
        expect(title).to.contains("collections");
        setTimeout(() => {
            callback(null, result)
        }, 10000)
    });
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

When(/^user navigates to "([^"]*)" article$/, function (articleNumber, callback) {
    this.state.driver.get(config.url + "articles/" + articleNumber).then((result) => {
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});

Then(/^the article type is "([^"]*)"$/, {timeout: 15 * 1000}, function (articleType, callback) {
    this.state.driver.findElement(By.xpath("//*[@id='maincontent']/header/div[4]/div/a")).getText()
        .then((result) => {
            expect(result).to.equal(articleType);
            callback(null, result)
        });
});
When(/^user clicks on 'Linked volume' of the first article$/, function (callback) {
    this.state.driver.findElement(By.xpath("//*[@id='listing']/li[1]/div/div/div/a"))
        .then((result) => {
            result.click();
            callback(null, result)
        });
    this.state.driver.takeScreenshot().then(buffer => {
        this.attach(buffer, 'image/png');
        callback(null, result)
    }).catch(err => {
        callback(err)
    })
});
Then(/^article preview doesn't contain date$/, function (callback) {
    this.state.driver.findElement(By.xpath('//*[@id="listing"]/li[2]/div/footer/div[1]/a')).getId().then((result) => {
        expect(result).not.equal("\\w{3}\\s\\d{2},\\s\\d{4}");
        callback(null, result)
    }).catch(err => {
        callback(err)
    });
});
Then(/^"([^"]*)" page is displayed$/, function (articleType, callback) {
    this.state.driver.findElement(By.xpath('//*[@id="maincontent"]//h1')).getText()
        .then((result) => {
            expect(result).to.equal(articleType);
            callback(null, result)
        });
});
Then(/^section "([^"]*)" is displayed$/, function (sectionName, callback) {
    this.state.driver.findElement(By.xpath('//*[@id="subjects"]//p')).getText()
        .then((result) => {
            expect(result).to.equal(sectionName);
            callback(null, result)
        });

});
Then(/^the following special type of articles is displayed:$/, async function (articleTypes) {
    const types = articleTypes.rawTable.flat()
    const elements = await this.state.driver.findElement(By.xpath('//*[@id="section-listing--types"]')).getText()
    expect(result).to.not.equal(null);
    const parsed = elements.split("\n")
    expect(parsed).to.eql(types)
});
When(/^user clicks on 'Download' button$/, {timeout: 20 * 1000}, function (callback) {
    this.state.driver.findElement(By.xpath('//a[@href="#downloads"]'))
        .then((result) => {
            result.click();
            callback(null, result)
        });
});
When(/^user selects "([^"]*)"$/, async function (extraRef) {
    const elemMap = {
        "BibTeX": '//*[@id="downloads"]/ul[2]/li[1]/a',
        "RIS": '//*[@id="downloads"]/ul[2]/li[2]/a',
        "Mendeley": '//*[@id="downloads"]/ul[3]/li[1]/a',
        "ReadCube": '//*[@id="downloads"]/ul[3]/li[2]/a',
        "Papers": '//*[@id="downloads"]/ul[3]/li[3]/a',
    }

    expect(elemMap[extraRef]).to.be.a('string');

    const result = await this.state.driver.findElement(By.xpath(elemMap[extraRef]))
    result.click();
});