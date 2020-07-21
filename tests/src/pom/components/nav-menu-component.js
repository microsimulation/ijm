// Nav Menu Component has the functionality to test the menu
// Component of the SLS SPA

import {By} from "selenium-webdriver";

const ComponentBase = require('../component-base');

class NavMenuComponent extends ComponentBase {
  constructor(
    webdriver,
    driver,
    waitTimeout = 20000,
  ) {
    super(webdriver, driver, waitTimeout);
  }

  async clickHome() {
    const result = await this.driver.findElement(
        By.xpath("//*[contains(text(), 'Home')]"), this.waitTimeout);
    result.click();
  }

  async clickIssues() {
    const result = await this.driver.findElement(
        By.xpath("//*[contains(text(), 'Issues')]"), this.waitTimeout);
    result.click();
  }
}

module.exports = NavMenuComponent;
