// Functionality for both external and internal page object
import {expect} from 'chai';

const ComponentBase = require('./component-base');

class PageBase extends ComponentBase {
  constructor(
    webdriver,
    driver,
    targetUrl,
    titleContents,
    waitTimeout = 10000,
  ) {
    super(webdriver, driver, waitTimeout);
    this.targetUrl = targetUrl;
    this.expectedTitle = titleContents;
    this.waitTimeout = waitTimeout;
  }

  async navigate() {
    await this.driver.navigate().to(this.targetUrl);
    await this.waitForTitle();
  }

  async waitForTitle() {
    this.log(`- Expecting page title to Contain: ${this.expectedTitle}`);
    const actualTitle = await this.state.driver.getTitle()
    this.log(`- Actual page title: ${this.expectedTitle}`);
    expect(actualTitle).to.equal(this.expectedTitle);
  }

  // returns from a navigation destination - like pressing browser back button
  async returnFromDestination() {
    return this.driver.navigate().back();
  }

  async refreshPage() {
    return this.driver.navigate().refresh();
  }
}

module.exports = PageBase;
