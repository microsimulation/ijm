'use strict';
const {Given,When,Then} = require('cucumber');

const assert = require('assert')

const webdriver = require('selenium-webdriver');
// defineSupportCode(function () {

// // The “then” condition for our test scenario // //
//     Then(/^I should be able to click Search in the sidebar $/, function (text, next) {
//         this.driver.findElement({id: 'searchText'}).click();
//         this.driver.findElement({id: 'searchText'}).sendKeys(text).then(next);
//     });

    Given(/^user navigates to 'Home' page$/, function () {
        return this.driver.get('http://elf-ijm-alb-387488728.eu-west-2.elb.amazonaws.com/').then(next);

    });
// });
