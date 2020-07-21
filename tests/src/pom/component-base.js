// Base class for all components including page-base and spa-page-base

class ComponentBase {
  constructor(
    webdriver,
    driver,
    waitTimeout = 10000,
  ) {
    this.webdriver = webdriver;
    this.driver = driver;
    this.waitTimeout = waitTimeout;
    this.log = myVar => process.stdout.write(`${myVar}\n`);
  }

  async waitForElement(selector, elementName, waitTimeout) {
    let result;
    await this.driver.wait(() =>
      this.driver.findElement(selector)
        .then(
          (element) => {
            result = element;
            return true;
          },
          (err) => {
            if (err.name === 'NoSuchElementError') {
              return false;
            }
            return true;
          },
        ), waitTimeout, `Unable to find element: ${elementName}`);
    return result;
  }

  async waitForElements(selector, elementsName, waitTimeout) {
    let result;
    await this.driver.wait(() =>
      this.driver.findElements(selector)
        .then(
          (elements) => {
            result = elements;
            return true;
          },
          (err) => {
            if (err.name === 'NoSuchElementsError') {
              return false;
            }
            return true;
          },
        ), waitTimeout, `Unable to find elements: ${elementsName}`);
    return result;
  }

  // output the messages from the webdriver browser console
  // Note: after we watch this for a while - we may want to add some asserts
  async dumpWebDriverLogs() {
    await this.driver.manage().logs().get('browser').then((logs) => {
      if (logs.length === 0) {
        this.log('- No items found in webdriver log');
      }
      this.log(`- logs.length: ${logs.length}`);
      logs.forEach((log) => {
        this.log(`- ${log.message}`);
      });
    });
  }
}

module.exports = ComponentBase;
