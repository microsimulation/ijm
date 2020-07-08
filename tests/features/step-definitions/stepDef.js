const {When, Then, Given,After} = require('cucumber');
const assert = require('assert')
const { By, until} = require('selenium-webdriver');

// // The “then” condition for our test scenario // //
//     Then(/^I should be able to click Search in the sidebar $/, function (text, next) {
//         this.driver.findElement({id: 'searchText'}).click();
//         this.driver.findElement({id: 'searchText'}).sendKeys(text).then(next);
//     });

    Given(/^user navigates to 'Home' page$/, {timeout: 10 * 1000}, function (callback) {

         this.state.driver.get('http://elf-ijm-alb-387488728.eu-west-2.elb.amazonaws.com/').then((result)=>{
             console.log(result)
             callback(null,result)

         }).catch(err =>{
             console.log(err)
             callback(err)
         })

    });

    Then(/^a list of articles is displayed$/, {timeout: 15 * 1000}, function (callback) {
        const mainContent=this.state.driver.findElement(By.xpath("//*[@id='maincontent']/div[2]/header/div/h1"));
        // assert.equal(mainContent,"International Journal of Microsimulation1");
        setTimeout(()=>{
            console.log("Wait for ")
            callback()
        },10000)
    });



When(/^user is on the Home page$/, function () {
    this.state.driver.getTitle().then(function (title) {
        assert.equal(title, "Latest research | International Journal of Microsimulation");
        return title;
    });

});
